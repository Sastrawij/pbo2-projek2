<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Layanan;
use App\Models\Mekanik;
use App\Models\Servis;
use App\Models\SukuCadang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ServisController extends Controller
{
    public function index(Request $request): View
    {
        $data = Servis::with('kendaraan.pelanggan', 'mekanik')
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->q, fn ($q, $kata) => $q->where(function ($w) use ($kata) {
                $w->where('no_servis', 'like', "%{$kata}%")
                    ->orWhereHas('kendaraan', fn ($k) => $k->where('no_polisi', 'like', "%{$kata}%"))
                    ->orWhereHas('kendaraan.pelanggan', fn ($p) => $p->where('nama', 'like', "%{$kata}%"));
            }))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('servis.index', compact('data'));
    }

    public function create(): View
    {
        return view('servis.form', array_merge(
            ['servis' => new Servis(['tanggal' => now()->toDateString(), 'status' => 'menunggu'])],
            $this->dataPilihan()
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $servis = $this->simpan($request);

        return redirect()->route('servis.show', $servis)->with('success', 'Data servis berhasil disimpan.');
    }

    public function show(Servis $servis): View
    {
        $servis->load('kendaraan.pelanggan', 'mekanik', 'layanan', 'sukuCadang');

        return view('servis.show', compact('servis'));
    }

    public function edit(Servis $servis): View
    {
        $servis->load('layanan', 'sukuCadang');

        return view('servis.form', array_merge(['servis' => $servis], $this->dataPilihan()));
    }

    public function update(Request $request, Servis $servis): RedirectResponse
    {
        $this->simpan($request, $servis);

        return redirect()->route('servis.show', $servis)->with('success', 'Data servis berhasil diperbarui.');
    }

    public function destroy(Servis $servis): RedirectResponse
    {
        DB::transaction(function () use ($servis) {
            // Kembalikan stok suku cadang yang dipakai
            foreach ($servis->sukuCadang as $sc) {
                SukuCadang::whereKey($sc->id)->increment('stok', $sc->pivot->jumlah);
            }

            $servis->delete(); // tabel detail ikut terhapus (cascade)
        });

        return redirect()->route('servis.index')->with('success', 'Data servis berhasil dihapus.');
    }

    public function ubahStatus(Request $request, Servis $servis): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Servis::STATUS))],
        ]);

        $servis->update($data);

        return back()->with('success', 'Status servis diubah menjadi ' . Servis::STATUS[$data['status']] . '.');
    }

    /** Data untuk dropdown pada form servis. */
    private function dataPilihan(): array
    {
        return [
            'kendaraan'  => Kendaraan::with('pelanggan')->orderBy('no_polisi')->get(),
            'mekanik'    => Mekanik::where('status', true)->orderBy('nama')->get(),
            'layanan'    => Layanan::orderBy('nama')->get(),
            'sukuCadang' => SukuCadang::orderBy('nama')->get(),
        ];
    }

    /** Membersihkan baris kosong dan menggabungkan duplikat dari form. */
    private function bersihkan(Request $request): void
    {
        $layanan = array_values(array_unique(array_filter((array) $request->input('layanan', []))));

        $suku = [];
        foreach ((array) $request->input('suku_cadang', []) as $baris) {
            $id = $baris['id'] ?? null;
            if (empty($id)) {
                continue;
            }
            $jumlah = (int) ($baris['jumlah'] ?? 1);
            if (isset($suku[$id])) {
                $suku[$id]['jumlah'] += $jumlah;
            } else {
                $suku[$id] = ['id' => $id, 'jumlah' => $jumlah];
            }
        }

        $request->merge([
            'layanan'     => $layanan,
            'suku_cadang' => array_values($suku),
        ]);
    }

    /** Simpan (baru atau ubah) servis beserta detailnya dalam satu transaksi. */
    private function simpan(Request $request, ?Servis $servis = null): Servis
    {
        $this->bersihkan($request);

        $data = $request->validate([
            'kendaraan_id'          => ['required', 'exists:kendaraan,id'],
            'mekanik_id'            => ['nullable', 'exists:mekanik,id'],
            'tanggal'               => ['required', 'date'],
            'keluhan'               => ['required', 'string'],
            'status'                => ['required', Rule::in(array_keys(Servis::STATUS))],
            'catatan'               => ['nullable', 'string'],
            'layanan'               => ['array'],
            'layanan.*'             => ['exists:layanan,id'],
            'suku_cadang'           => ['array'],
            'suku_cadang.*.id'      => ['required', 'exists:suku_cadang,id'],
            'suku_cadang.*.jumlah'  => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($data, &$servis) {
            $kolom = Arr::only($data, ['kendaraan_id', 'mekanik_id', 'tanggal', 'keluhan', 'status', 'catatan']);

            if ($servis) {
                // Kembalikan dulu stok lama, lalu hitung ulang dari data baru
                foreach ($servis->sukuCadang as $sc) {
                    SukuCadang::whereKey($sc->id)->increment('stok', $sc->pivot->jumlah);
                }
                $servis->update($kolom);
            } else {
                $servis = Servis::create($kolom + ['no_servis' => Servis::buatNomor()]);
            }

            $total = 0;

            // Layanan (harga disimpan sebagai harga saat transaksi)
            $syncLayanan = [];
            foreach (Layanan::whereIn('id', $data['layanan'] ?? [])->get() as $l) {
                $syncLayanan[$l->id] = ['harga' => $l->harga];
                $total += $l->harga;
            }
            $servis->layanan()->sync($syncLayanan);

            // Suku cadang + pengurangan stok
            $syncSuku = [];
            foreach ($data['suku_cadang'] ?? [] as $baris) {
                $sc = SukuCadang::lockForUpdate()->findOrFail($baris['id']);

                if ($sc->stok < $baris['jumlah']) {
                    throw ValidationException::withMessages([
                        'suku_cadang' => "Stok {$sc->nama} tidak cukup (tersisa {$sc->stok}).",
                    ]);
                }

                $sc->decrement('stok', $baris['jumlah']);
                $syncSuku[$sc->id] = ['jumlah' => $baris['jumlah'], 'harga' => $sc->harga];
                $total += $sc->harga * $baris['jumlah'];
            }
            $servis->sukuCadang()->sync($syncSuku);

            $servis->update(['total' => $total]);
        });

        return $servis;
    }
}

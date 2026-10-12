<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KendaraanController extends Controller
{
    public function index(Request $request): View
    {
        $data = Kendaraan::with('pelanggan')
            ->when($request->q, fn ($q, $kata) => $q->where(function ($w) use ($kata) {
                $w->where('no_polisi', 'like', "%{$kata}%")
                    ->orWhere('merk', 'like', "%{$kata}%")
                    ->orWhere('tipe', 'like', "%{$kata}%")
                    ->orWhereHas('pelanggan', fn ($p) => $p->where('nama', 'like', "%{$kata}%"));
            }))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('kendaraan.index', compact('data'));
    }

    public function create(): View
    {
        return view('kendaraan.form', [
            'item'      => new Kendaraan(),
            'pelanggan' => Pelanggan::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kendaraan::create($this->validasi($request));

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan): View
    {
        return view('kendaraan.form', [
            'item'      => $kendaraan,
            'pelanggan' => Pelanggan::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Kendaraan $kendaraan): RedirectResponse
    {
        $kendaraan->update($this->validasi($request, $kendaraan));

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan): RedirectResponse
    {
        try {
            $kendaraan->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Kendaraan tidak bisa dihapus karena sudah punya riwayat servis.');
        }

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    private function validasi(Request $request, ?Kendaraan $kendaraan = null): array
    {
        return $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggan,id'],
            'no_polisi'    => ['required', 'string', 'max:15', Rule::unique('kendaraan', 'no_polisi')->ignore($kendaraan?->id)],
            'jenis'        => ['required', Rule::in(['Motor', 'Mobil'])],
            'merk'         => ['required', 'string', 'max:50'],
            'tipe'         => ['required', 'string', 'max:50'],
            'tahun'        => ['nullable', 'integer', 'min:1980', 'max:' . (now()->year + 1)],
            'warna'        => ['nullable', 'string', 'max:30'],
        ]);
    }
}

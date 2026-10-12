<?php

namespace App\Http\Controllers;

use App\Models\SukuCadang;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SukuCadangController extends Controller
{
    public function index(Request $request): View
    {
        $data = SukuCadang::when($request->q, fn ($q, $kata) => $q->where(function ($w) use ($kata) {
            $w->where('nama', 'like', "%{$kata}%")->orWhere('kode', 'like', "%{$kata}%");
        }))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('suku-cadang.index', compact('data'));
    }

    public function create(): View
    {
        return view('suku-cadang.form', ['item' => new SukuCadang()]);
    }

    public function store(Request $request): RedirectResponse
    {
        SukuCadang::create($this->validasi($request));

        return redirect()->route('suku-cadang.index')->with('success', 'Suku cadang berhasil ditambahkan.');
    }

    public function edit(SukuCadang $sukuCadang): View
    {
        return view('suku-cadang.form', ['item' => $sukuCadang]);
    }

    public function update(Request $request, SukuCadang $sukuCadang): RedirectResponse
    {
        $sukuCadang->update($this->validasi($request, $sukuCadang));

        return redirect()->route('suku-cadang.index')->with('success', 'Suku cadang berhasil diperbarui.');
    }

    public function destroy(SukuCadang $sukuCadang): RedirectResponse
    {
        try {
            $sukuCadang->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Suku cadang tidak bisa dihapus karena sudah dipakai pada data servis.');
        }

        return redirect()->route('suku-cadang.index')->with('success', 'Suku cadang berhasil dihapus.');
    }

    private function validasi(Request $request, ?SukuCadang $sukuCadang = null): array
    {
        return $request->validate([
            'kode'  => ['required', 'string', 'max:20', Rule::unique('suku_cadang', 'kode')->ignore($sukuCadang?->id)],
            'nama'  => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok'  => ['required', 'integer', 'min:0'],
        ]);
    }
}

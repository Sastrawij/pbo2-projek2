<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(Request $request): View
    {
        $data = Layanan::when($request->q, fn ($q, $kata) => $q->where('nama', 'like', "%{$kata}%"))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('layanan.index', compact('data'));
    }

    public function create(): View
    {
        return view('layanan.form', ['item' => new Layanan()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Layanan::create($this->validasi($request));

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan): View
    {
        return view('layanan.form', ['item' => $layanan]);
    }

    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $layanan->update($this->validasi($request));

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        try {
            $layanan->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Layanan tidak bisa dihapus karena sudah dipakai pada data servis.');
        }

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'harga'     => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);
    }
}

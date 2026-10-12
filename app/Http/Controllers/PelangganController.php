<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganController extends Controller
{
    public function index(Request $request): View
    {
        $data = Pelanggan::withCount('kendaraan')
            ->when($request->q, fn ($q, $kata) => $q->where(function ($w) use ($kata) {
                $w->where('nama', 'like', "%{$kata}%")->orWhere('no_hp', 'like', "%{$kata}%");
            }))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pelanggan.index', compact('data'));
    }

    public function create(): View
    {
        return view('pelanggan.form', ['item' => new Pelanggan()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Pelanggan::create($this->validasi($request));

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan): View
    {
        return view('pelanggan.form', ['item' => $pelanggan]);
    }

    public function update(Request $request, Pelanggan $pelanggan): RedirectResponse
    {
        $pelanggan->update($this->validasi($request));

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan): RedirectResponse
    {
        try {
            $pelanggan->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Pelanggan tidak bisa dihapus karena masih memiliki kendaraan.');
        }

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama'   => ['required', 'string', 'max:255'],
            'no_hp'  => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);
    }
}

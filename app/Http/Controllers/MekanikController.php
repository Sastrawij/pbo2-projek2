<?php

namespace App\Http\Controllers;

use App\Models\Mekanik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MekanikController extends Controller
{
    public function index(Request $request): View
    {
        $data = Mekanik::withCount('servis')
            ->when($request->q, fn ($q, $kata) => $q->where(function ($w) use ($kata) {
                $w->where('nama', 'like', "%{$kata}%")->orWhere('keahlian', 'like', "%{$kata}%");
            }))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('mekanik.index', compact('data'));
    }

    public function create(): View
    {
        return view('mekanik.form', ['item' => new Mekanik(['status' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Mekanik::create($this->validasi($request));

        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil ditambahkan.');
    }

    public function edit(Mekanik $mekanik): View
    {
        return view('mekanik.form', ['item' => $mekanik]);
    }

    public function update(Request $request, Mekanik $mekanik): RedirectResponse
    {
        $mekanik->update($this->validasi($request));

        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil diperbarui.');
    }

    public function destroy(Mekanik $mekanik): RedirectResponse
    {
        // Riwayat servis tetap ada: kolom mekanik_id otomatis menjadi NULL (nullOnDelete)
        $mekanik->delete();

        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'no_hp'    => ['nullable', 'string', 'max:20'],
            'keahlian' => ['nullable', 'string', 'max:100'],
        ]);

        $data['status'] = $request->boolean('status');

        return $data;
    }
}

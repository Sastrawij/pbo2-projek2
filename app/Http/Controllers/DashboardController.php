<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Servis;
use App\Models\SukuCadang;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $pendapatanBulanIni = Servis::whereIn('status', ['selesai', 'diambil'])
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->sum('total');

        return view('dashboard', [
            'jumlahPelanggan'    => Pelanggan::count(),
            'jumlahKendaraan'    => Kendaraan::count(),
            'servisAktif'        => Servis::whereIn('status', ['menunggu', 'dikerjakan'])->count(),
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'servisTerbaru'      => Servis::with('kendaraan.pelanggan', 'mekanik')->latest('id')->take(5)->get(),
            'stokMenipis'        => SukuCadang::where('stok', '<=', 5)->orderBy('stok')->get(),
        ]);
    }
}

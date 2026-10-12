<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MekanikController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\SukuCadangController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// Halaman untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

// Halaman yang wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('pelanggan', PelangganController::class)->except('show');
    Route::resource('kendaraan', KendaraanController::class)->except('show');
    Route::resource('mekanik', MekanikController::class)->except('show');
    Route::resource('layanan', LayananController::class)->except('show');
    Route::resource('suku-cadang', SukuCadangController::class)
        ->except('show')
        ->parameters(['suku-cadang' => 'sukuCadang']);

    Route::patch('servis/{servis}/status', [ServisController::class, 'ubahStatus'])->name('servis.status');
    Route::resource('servis', ServisController::class)->parameters(['servis' => 'servis']);
});

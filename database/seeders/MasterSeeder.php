<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Layanan;
use App\Models\Mekanik;
use App\Models\Pelanggan;
use App\Models\SukuCadang;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // Pelanggan
        $budi  = Pelanggan::create(['nama' => 'Budi Santoso',  'no_hp' => '081250001111', 'alamat' => 'Jl. A. Yani Km 3, Banjarmasin']);
        $siti  = Pelanggan::create(['nama' => 'Siti Aminah',   'no_hp' => '081350002222', 'alamat' => 'Jl. Sultan Adam, Banjarmasin']);
        $rizky = Pelanggan::create(['nama' => 'Rizky Pratama', 'no_hp' => '082150003333', 'alamat' => 'Jl. Kayu Tangi, Banjarmasin']);
        $dewi  = Pelanggan::create(['nama' => 'Dewi Lestari',  'no_hp' => '085250004444', 'alamat' => 'Jl. Gatot Subroto, Banjarmasin']);

        // Kendaraan
        Kendaraan::create(['pelanggan_id' => $budi->id,  'no_polisi' => 'DA 1234 AB', 'jenis' => 'Motor', 'merk' => 'Honda',  'tipe' => 'Vario 125',  'tahun' => 2020, 'warna' => 'Hitam']);
        Kendaraan::create(['pelanggan_id' => $budi->id,  'no_polisi' => 'DA 5678 CD', 'jenis' => 'Mobil', 'merk' => 'Toyota', 'tipe' => 'Avanza',     'tahun' => 2018, 'warna' => 'Silver']);
        Kendaraan::create(['pelanggan_id' => $siti->id,  'no_polisi' => 'DA 2468 EF', 'jenis' => 'Motor', 'merk' => 'Yamaha', 'tipe' => 'NMAX',       'tahun' => 2022, 'warna' => 'Abu-abu']);
        Kendaraan::create(['pelanggan_id' => $rizky->id, 'no_polisi' => 'DA 1357 GH', 'jenis' => 'Mobil', 'merk' => 'Daihatsu', 'tipe' => 'Xenia',     'tahun' => 2019, 'warna' => 'Putih']);
        Kendaraan::create(['pelanggan_id' => $dewi->id,  'no_polisi' => 'DA 9753 IJ', 'jenis' => 'Motor', 'merk' => 'Honda',  'tipe' => 'Beat',       'tahun' => 2021, 'warna' => 'Merah']);

        // Mekanik
        Mekanik::create(['nama' => 'Agus Salim',  'no_hp' => '081111000001', 'keahlian' => 'Mesin motor', 'status' => true]);
        Mekanik::create(['nama' => 'Hendra',      'no_hp' => '081111000002', 'keahlian' => 'Mesin mobil', 'status' => true]);
        Mekanik::create(['nama' => 'Muhammad Noor', 'no_hp' => '081111000003', 'keahlian' => 'Kelistrikan', 'status' => false]);

        // Layanan
        Layanan::create(['nama' => 'Ganti Oli',          'harga' => 30000,  'deskripsi' => 'Biaya jasa ganti oli (oli dihitung terpisah)']);
        Layanan::create(['nama' => 'Servis Ringan',      'harga' => 75000,  'deskripsi' => 'Pengecekan umum dan penyetelan']);
        Layanan::create(['nama' => 'Servis Besar',       'harga' => 250000, 'deskripsi' => 'Turun mesin dan pembersihan menyeluruh']);
        Layanan::create(['nama' => 'Ganti Kampas Rem',   'harga' => 50000,  'deskripsi' => 'Biaya jasa pasang kampas rem']);
        Layanan::create(['nama' => 'Tune Up',            'harga' => 100000, 'deskripsi' => 'Pengaturan karburator/injeksi dan pengapian']);
        Layanan::create(['nama' => 'Spooring Balancing', 'harga' => 150000, 'deskripsi' => 'Khusus mobil']);

        // Suku cadang
        SukuCadang::create(['kode' => 'OLI-001', 'nama' => 'Oli Mesin Motor 0.8L', 'harga' => 45000,  'stok' => 30]);
        SukuCadang::create(['kode' => 'OLI-002', 'nama' => 'Oli Mesin Mobil 4L',   'harga' => 280000, 'stok' => 12]);
        SukuCadang::create(['kode' => 'KMP-001', 'nama' => 'Kampas Rem Depan Motor', 'harga' => 55000, 'stok' => 15]);
        SukuCadang::create(['kode' => 'KMP-002', 'nama' => 'Kampas Rem Depan Mobil', 'harga' => 180000, 'stok' => 8]);
        SukuCadang::create(['kode' => 'BUS-001', 'nama' => 'Busi Motor',            'harga' => 25000,  'stok' => 40]);
        SukuCadang::create(['kode' => 'FLT-001', 'nama' => 'Filter Udara Mobil',    'harga' => 95000,  'stok' => 4]);
        SukuCadang::create(['kode' => 'AKI-001', 'nama' => 'Aki 12V 5Ah',           'harga' => 220000, 'stok' => 3]);
    }
}

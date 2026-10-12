<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Layanan;
use App\Models\Mekanik;
use App\Models\Servis;
use App\Models\SukuCadang;
use Illuminate\Database\Seeder;

class ServisSeeder extends Seeder
{
    public function run(): void
    {
        $this->buat('DA 1234 AB', 'Agus Salim', now()->subDays(2)->toDateString(), 'Oli sudah lama belum diganti dan mesin kasar', 'selesai',
            ['Ganti Oli', 'Tune Up'], [['OLI-001', 1], ['BUS-001', 1]]);

        $this->buat('DA 5678 CD', 'Hendra', now()->subDay()->toDateString(), 'Rem berdecit saat pengereman', 'dikerjakan',
            ['Ganti Kampas Rem'], [['KMP-002', 1]]);

        $this->buat('DA 2468 EF', 'Agus Salim', now()->toDateString(), 'Servis rutin 5.000 km', 'menunggu',
            ['Servis Ringan', 'Ganti Oli'], [['OLI-001', 1]]);
    }

    private function buat(string $noPolisi, string $mekanik, string $tanggal, string $keluhan, string $status, array $layanan, array $suku): void
    {
        $servis = Servis::create([
            'no_servis'    => Servis::buatNomor(),
            'kendaraan_id' => Kendaraan::where('no_polisi', $noPolisi)->value('id'),
            'mekanik_id'   => Mekanik::where('nama', $mekanik)->value('id'),
            'tanggal'      => $tanggal,
            'keluhan'      => $keluhan,
            'status'       => $status,
        ]);

        $total = 0;

        foreach ($layanan as $nama) {
            $l = Layanan::where('nama', $nama)->firstOrFail();
            $servis->layanan()->attach($l->id, ['harga' => $l->harga]);
            $total += $l->harga;
        }

        foreach ($suku as [$kode, $jumlah]) {
            $s = SukuCadang::where('kode', $kode)->firstOrFail();
            $servis->sukuCadang()->attach($s->id, ['jumlah' => $jumlah, 'harga' => $s->harga]);
            $s->decrement('stok', $jumlah);
            $total += $s->harga * $jumlah;
        }

        $servis->update(['total' => $total]);
    }
}

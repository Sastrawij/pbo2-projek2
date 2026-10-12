@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner"><h3>{{ $jumlahPelanggan }}</h3><p>Pelanggan</p></div>
            <i class="small-box-icon bi bi-people-fill"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner"><h3>{{ $jumlahKendaraan }}</h3><p>Kendaraan Terdaftar</p></div>
            <i class="small-box-icon bi bi-car-front-fill"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner"><h3>{{ $servisAktif }}</h3><p>Servis Berjalan</p></div>
            <i class="small-box-icon bi bi-tools"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner"><h3 class="fs-4">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3><p>Pendapatan Bulan Ini</p></div>
            <i class="small-box-icon bi bi-cash-coin"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Servis Terbaru</h3>
                <div class="card-tools"><a href="{{ route('servis.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a></div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr><th>No. Servis</th><th>Kendaraan</th><th>Pelanggan</th><th>Status</th><th class="text-end">Total</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($servisTerbaru as $s)
                        <tr>
                            <td><a href="{{ route('servis.show', $s) }}">{{ $s->no_servis }}</a></td>
                            <td>{{ $s->kendaraan->no_polisi }}<br><small class="text-secondary">{{ $s->kendaraan->merk }} {{ $s->kendaraan->tipe }}</small></td>
                            <td>{{ $s->kendaraan->pelanggan->nama }}</td>
                            <td><span class="badge text-bg-{{ $s->status_badge }}">{{ $s->status_label }}</span></td>
                            <td class="text-end">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada data servis.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Stok Menipis (&le; 5)</h3></div>
            <ul class="list-group list-group-flush">
                @forelse ($stokMenipis as $sc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $sc->nama }}<br><small class="text-secondary">{{ $sc->kode }}</small></span>
                        <span class="badge text-bg-{{ $sc->stok == 0 ? 'danger' : 'warning' }}">{{ $sc->stok }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-secondary">Semua stok aman.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Servis')

@push('styles')
<style>
    @media print {
        .app-header, .app-sidebar, .app-footer, .app-content-header, .no-print { display: none !important; }
        .app-wrapper { display: block !important; }
        .app-main { margin: 0 !important; padding: 0 !important; }
        .card { border: 0 !important; box-shadow: none !important; }
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="{{ route('servis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div>
        <button type="button" class="btn btn-outline-dark" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Nota</button>
        <a href="{{ route('servis.edit', $servis) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Ubah</a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Nota Servis {{ $servis->no_servis }}</h3>
        <span class="badge text-bg-{{ $servis->status_badge }} fs-6">{{ $servis->status_label }}</span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th style="width:130px">Tanggal</th><td>{{ $servis->tanggal->format('d/m/Y') }}</td></tr>
                    <tr><th>Pelanggan</th><td>{{ $servis->kendaraan->pelanggan->nama }} ({{ $servis->kendaraan->pelanggan->no_hp }})</td></tr>
                    <tr><th>Mekanik</th><td>{{ $servis->mekanik?->nama ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th style="width:130px">No. Polisi</th><td>{{ $servis->kendaraan->no_polisi }}</td></tr>
                    <tr><th>Kendaraan</th><td>{{ $servis->kendaraan->merk }} {{ $servis->kendaraan->tipe }} ({{ $servis->kendaraan->jenis }})</td></tr>
                    <tr><th>Keluhan</th><td>{{ $servis->keluhan }}</td></tr>
                </table>
            </div>
        </div>

        <h5>Layanan</h5>
        <table class="table table-sm table-bordered">
            <thead><tr><th>Layanan</th><th class="text-end" style="width:180px">Biaya</th></tr></thead>
            <tbody>
            @forelse ($servis->layanan as $l)
                <tr><td>{{ $l->nama }}</td><td class="text-end">Rp {{ number_format($l->pivot->harga, 0, ',', '.') }}</td></tr>
            @empty
                <tr><td colspan="2" class="text-center text-secondary">Tidak ada layanan.</td></tr>
            @endforelse
            </tbody>
        </table>

        <h5>Suku Cadang</h5>
        <table class="table table-sm table-bordered">
            <thead>
            <tr><th>Suku Cadang</th><th class="text-center" style="width:90px">Jumlah</th><th class="text-end" style="width:150px">Harga</th><th class="text-end" style="width:180px">Subtotal</th></tr>
            </thead>
            <tbody>
            @forelse ($servis->sukuCadang as $s)
                <tr>
                    <td>{{ $s->nama }} <small class="text-secondary">({{ $s->kode }})</small></td>
                    <td class="text-center">{{ $s->pivot->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($s->pivot->harga, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($s->pivot->harga * $s->pivot->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-secondary">Tidak ada suku cadang.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="text-end fs-4 mb-3">Total: <strong>Rp {{ number_format($servis->total, 0, ',', '.') }}</strong></div>

        @if ($servis->catatan)
            <p class="mb-0"><strong>Catatan:</strong> {{ $servis->catatan }}</p>
        @endif
    </div>

    <div class="card-footer no-print">
        <form action="{{ route('servis.status', $servis) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf @method('PATCH')
            <label class="mb-0">Ubah status:</label>
            <select name="status" class="form-select form-select-sm" style="width:auto">
                @foreach (\App\Models\Servis::STATUS as $kode => $label)
                    <option value="{{ $kode }}" @selected($servis->status == $kode)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary">Simpan status</button>
        </form>
    </div>
</div>
@endsection

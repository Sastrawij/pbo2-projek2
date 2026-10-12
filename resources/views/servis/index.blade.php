@extends('layouts.app')

@section('title', 'Servis')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari no. servis / no. polisi / pelanggan">
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">Semua status</option>
                @foreach (\App\Models\Servis::STATUS as $kode => $label)
                    <option value="{{ $kode }}" @selected(request('status') == $kode)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('servis.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Servis Baru</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>No. Servis</th><th>Tanggal</th><th>Kendaraan</th><th>Pelanggan</th><th>Mekanik</th>
                <th>Status</th><th class="text-end">Total</th><th style="width:150px">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($data as $row)
                <tr>
                    <td><a href="{{ route('servis.show', $row) }}">{{ $row->no_servis }}</a></td>
                    <td>{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $row->kendaraan->no_polisi }}<br><small class="text-secondary">{{ $row->kendaraan->merk }} {{ $row->kendaraan->tipe }}</small></td>
                    <td>{{ $row->kendaraan->pelanggan->nama }}</td>
                    <td>{{ $row->mekanik?->nama ?? '-' }}</td>
                    <td><span class="badge text-bg-{{ $row->status_badge }}">{{ $row->status_label }}</span></td>
                    <td class="text-end">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('servis.show', $row) }}" class="btn btn-sm btn-info" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('servis.edit', $row) }}" class="btn btn-sm btn-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('servis.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data servis ini? Stok suku cadang akan dikembalikan.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-secondary py-4">Belum ada data servis.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($data->hasPages())
        <div class="card-footer">{{ $data->links() }}</div>
    @endif
</div>
@endsection

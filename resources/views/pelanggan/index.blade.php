@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <form method="GET" class="d-flex">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm me-2" placeholder="Cari nama / no. HP">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
            <tr><th style="width:50px">No</th><th>Nama</th><th>No. HP</th><th>Alamat</th><th>Kendaraan</th><th style="width:120px">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $data->firstItem() + $loop->index }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->no_hp }}</td>
                    <td>{{ $row->alamat ?: '-' }}</td>
                    <td><span class="badge text-bg-secondary">{{ $row->kendaraan_count }}</span></td>
                    <td>
                        <a href="{{ route('pelanggan.edit', $row) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('pelanggan.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($data->hasPages())
        <div class="card-footer">{{ $data->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Suku Cadang')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <form method="GET" class="d-flex">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm me-2" placeholder="Cari kode / nama">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('suku-cadang.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
            <tr><th style="width:50px">No</th><th>Kode</th><th>Nama</th><th class="text-end">Harga</th><th class="text-center">Stok</th><th style="width:120px">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $data->firstItem() + $loop->index }}</td>
                    <td>{{ $row->kode }}</td>
                    <td>{{ $row->nama }}</td>
                    <td class="text-end">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge text-bg-{{ $row->stok == 0 ? 'danger' : ($row->stok <= 5 ? 'warning' : 'success') }}">{{ $row->stok }}</span>
                    </td>
                    <td>
                        <a href="{{ route('suku-cadang.edit', $row) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('suku-cadang.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus suku cadang ini?')">
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

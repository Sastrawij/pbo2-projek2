@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Layanan' : 'Tambah Layanan')

@section('content')
<div class="card">
    <form action="{{ $item->exists ? route('layanan.update', $item) : route('layanan.store') }}" method="POST">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" min="0" value="{{ old('harga', $item->harga ?? 0) }}" class="form-control @error('harga') is-invalid @enderror" required>
                @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('layanan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

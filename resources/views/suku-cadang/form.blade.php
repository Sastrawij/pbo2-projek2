@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Suku Cadang' : 'Tambah Suku Cadang')

@section('content')
<div class="card">
    <form action="{{ $item->exists ? route('suku-cadang.update', $item) : route('suku-cadang.store') }}" method="POST">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}" class="form-control @error('kode') is-invalid @enderror" placeholder="OLI-001" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" min="0" value="{{ old('harga', $item->harga ?? 0) }}" class="form-control @error('harga') is-invalid @enderror" required>
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" min="0" value="{{ old('stok', $item->stok ?? 0) }}" class="form-control @error('stok') is-invalid @enderror" required>
                    @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('suku-cadang.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

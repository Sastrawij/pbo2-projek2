@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Pelanggan' : 'Tambah Pelanggan')

@section('content')
<div class="card">
    <form action="{{ $item->exists ? route('pelanggan.update', $item) : route('pelanggan.store') }}" method="POST">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">No. HP <span class="text-danger">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $item->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror" required>
                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $item->alamat) }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

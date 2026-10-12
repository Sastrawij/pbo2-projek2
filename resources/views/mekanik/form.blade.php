@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Mekanik' : 'Tambah Mekanik')

@section('content')
<div class="card">
    <form action="{{ $item->exists ? route('mekanik.update', $item) : route('mekanik.store') }}" method="POST">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $item->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror">
                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Keahlian</label>
                    <input type="text" name="keahlian" value="{{ old('keahlian', $item->keahlian) }}" class="form-control @error('keahlian') is-invalid @enderror" placeholder="Mesin motor, kelistrikan, ...">
                    @error('keahlian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-check form-switch">
                <input type="hidden" name="status" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked(old('status', $item->status))>
                <label class="form-check-label" for="status">Aktif (bisa dipilih pada servis baru)</label>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('mekanik.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

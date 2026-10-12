@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Kendaraan' : 'Tambah Kendaraan')

@section('content')
<div class="card">
    <form action="{{ $item->exists ? route('kendaraan.update', $item) : route('kendaraan.store') }}" method="POST">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pemilik <span class="text-danger">*</span></label>
                    <select name="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih pelanggan --</option>
                        @foreach ($pelanggan as $p)
                            <option value="{{ $p->id }}" @selected(old('pelanggan_id', $item->pelanggan_id) == $p->id)>{{ $p->nama }} ({{ $p->no_hp }})</option>
                        @endforeach
                    </select>
                    @error('pelanggan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Polisi <span class="text-danger">*</span></label>
                    <input type="text" name="no_polisi" value="{{ old('no_polisi', $item->no_polisi) }}" class="form-control @error('no_polisi') is-invalid @enderror" placeholder="DA 1234 AB" required>
                    @error('no_polisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        @foreach (['Motor', 'Mobil'] as $j)
                            <option value="{{ $j }}" @selected(old('jenis', $item->jenis ?? 'Motor') == $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" name="merk" value="{{ old('merk', $item->merk) }}" class="form-control @error('merk') is-invalid @enderror" required>
                    @error('merk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <input type="text" name="tipe" value="{{ old('tipe', $item->tipe) }}" class="form-control @error('tipe') is-invalid @enderror" required>
                    @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" value="{{ old('tahun', $item->tahun) }}" class="form-control @error('tahun') is-invalid @enderror">
                    @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Warna</label>
                <input type="text" name="warna" value="{{ old('warna', $item->warna) }}" class="form-control @error('warna') is-invalid @enderror">
                @error('warna') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

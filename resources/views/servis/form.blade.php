@extends('layouts.app')

@section('title', $servis->exists ? 'Ubah Servis ' . $servis->no_servis : 'Servis Baru')

@php
    // Layanan yang tercentang
    $layananTerpilih = collect(old('layanan', $servis->exists ? $servis->layanan->pluck('id')->all() : []))
        ->map(fn ($id) => (int) $id)->all();

    // Baris suku cadang yang sudah ada
    $sukuTerpilih = old('suku_cadang', $servis->exists
        ? $servis->sukuCadang->map(fn ($s) => ['id' => $s->id, 'jumlah' => $s->pivot->jumlah])->all()
        : []);

    // Stok yang boleh dipakai = stok sekarang + jumlah yang sudah dipakai servis ini
    $sudahDipakai = $servis->exists ? $servis->sukuCadang->pluck('pivot.jumlah', 'id') : collect();
    $dataSuku = $sukuCadang->map(fn ($s) => [
        'id'    => $s->id,
        'kode'  => $s->kode,
        'nama'  => $s->nama,
        'harga' => (int) $s->harga,
        'stok'  => (int) $s->stok + (int) ($sudahDipakai[$s->id] ?? 0),
    ])->values();
@endphp

@section('content')
<form action="{{ $servis->exists ? route('servis.update', $servis) : route('servis.store') }}" method="POST">
    @csrf
    @if ($servis->exists) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title">Data Servis</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                        <select name="kendaraan_id" class="form-select @error('kendaraan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih kendaraan --</option>
                            @foreach ($kendaraan as $k)
                                <option value="{{ $k->id }}" @selected(old('kendaraan_id', $servis->kendaraan_id) == $k->id)>
                                    {{ $k->no_polisi }} - {{ $k->merk }} {{ $k->tipe }} ({{ $k->pelanggan->nama }})
                                </option>
                            @endforeach
                        </select>
                        @error('kendaraan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', optional($servis->tanggal)->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mekanik</label>
                            <select name="mekanik_id" class="form-select @error('mekanik_id') is-invalid @enderror">
                                <option value="">-- Belum ditentukan --</option>
                                @foreach ($mekanik as $m)
                                    <option value="{{ $m->id }}" @selected(old('mekanik_id', $servis->mekanik_id) == $m->id)>{{ $m->nama }}</option>
                                @endforeach
                            </select>
                            @error('mekanik_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach (\App\Models\Servis::STATUS as $kode => $label)
                                    <option value="{{ $kode }}" @selected(old('status', $servis->status) == $kode)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keluhan <span class="text-danger">*</span></label>
                        <textarea name="keluhan" rows="3" class="form-control @error('keluhan') is-invalid @enderror" required>{{ old('keluhan', $servis->keluhan) }}</textarea>
                        @error('keluhan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" rows="2" class="form-control">{{ old('catatan', $servis->catatan) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title">Layanan</h3></div>
                <div class="card-body">
                    @foreach ($layanan as $l)
                        <div class="form-check mb-1">
                            <input class="form-check-input cek-layanan" type="checkbox" name="layanan[]" id="layanan{{ $l->id }}"
                                   value="{{ $l->id }}" data-harga="{{ $l->harga }}" @checked(in_array($l->id, $layananTerpilih))>
                            <label class="form-check-label d-flex justify-content-between" for="layanan{{ $l->id }}">
                                <span>{{ $l->nama }}</span>
                                <span class="text-secondary ms-3">Rp {{ number_format($l->harga, 0, ',', '.') }}</span>
                            </label>
                        </div>
                    @endforeach
                    @if ($layanan->isEmpty())
                        <span class="text-secondary">Belum ada data layanan.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Suku Cadang yang Dipakai</h3>
            <button type="button" class="btn btn-sm btn-outline-primary" id="tambahSuku"><i class="bi bi-plus-lg"></i> Tambah baris</button>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table mb-0 align-middle">
                <thead>
                <tr><th>Suku cadang</th><th style="width:110px">Jumlah</th><th class="text-end" style="width:150px">Harga</th><th class="text-end" style="width:160px">Subtotal</th><th style="width:50px"></th></tr>
                </thead>
                <tbody id="barisSuku"></tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-secondary">Stok akan otomatis berkurang saat disimpan.</span>
            <span class="fs-5">Estimasi total: <strong id="totalEstimasi">Rp 0</strong></span>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
    <a href="{{ $servis->exists ? route('servis.show', $servis) : route('servis.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection

@push('scripts')
<script>
    const dataSuku = @json($dataSuku);
    const barisAwal = @json(array_values($sukuTerpilih));
    const tbody = document.getElementById('barisSuku');
    let urut = 0;

    const rupiah = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

    function tambahBaris(idTerpilih = '', jumlah = 1) {
        const i = urut++;
        const tr = document.createElement('tr');

        // kolom pilih suku cadang
        const tdPilih = document.createElement('td');
        const sel = document.createElement('select');
        sel.name = `suku_cadang[${i}][id]`;
        sel.className = 'form-select form-select-sm pilih-suku';
        const kosong = new Option('-- Pilih suku cadang --', '');
        sel.add(kosong);
        dataSuku.forEach((s) => {
            const opt = new Option(`${s.kode} - ${s.nama} (stok ${s.stok})`, s.id);
            if (String(s.id) === String(idTerpilih)) opt.selected = true;
            sel.add(opt);
        });
        tdPilih.appendChild(sel);

        // kolom jumlah
        const tdJumlah = document.createElement('td');
        const inp = document.createElement('input');
        inp.type = 'number'; inp.min = 1; inp.value = jumlah;
        inp.name = `suku_cadang[${i}][jumlah]`;
        inp.className = 'form-control form-control-sm input-jumlah';
        tdJumlah.appendChild(inp);

        // kolom harga, subtotal, hapus
        const tdHarga = document.createElement('td'); tdHarga.className = 'text-end harga-satuan';
        const tdSub = document.createElement('td'); tdSub.className = 'text-end subtotal';
        const tdHapus = document.createElement('td');
        const btn = document.createElement('button');
        btn.type = 'button'; btn.className = 'btn btn-sm btn-outline-danger';
        btn.innerHTML = '<i class="bi bi-x-lg"></i>';
        btn.addEventListener('click', () => { tr.remove(); hitung(); });
        tdHapus.appendChild(btn);

        tr.append(tdPilih, tdJumlah, tdHarga, tdSub, tdHapus);
        tbody.appendChild(tr);

        sel.addEventListener('change', hitung);
        inp.addEventListener('input', hitung);
        hitung();
    }

    function hitung() {
        let total = 0;

        document.querySelectorAll('.cek-layanan:checked').forEach((c) => {
            total += Number(c.dataset.harga);
        });

        tbody.querySelectorAll('tr').forEach((tr) => {
            const id = tr.querySelector('.pilih-suku').value;
            const jumlah = Number(tr.querySelector('.input-jumlah').value) || 0;
            const s = dataSuku.find((x) => String(x.id) === String(id));
            const harga = s ? s.harga : 0;
            const sub = harga * jumlah;
            tr.querySelector('.harga-satuan').textContent = s ? rupiah(harga) : '-';
            tr.querySelector('.subtotal').textContent = s ? rupiah(sub) : '-';
            if (s) tr.querySelector('.input-jumlah').max = s.stok;
            total += sub;
        });

        document.getElementById('totalEstimasi').textContent = rupiah(total);
    }

    document.getElementById('tambahSuku').addEventListener('click', () => tambahBaris());
    document.querySelectorAll('.cek-layanan').forEach((c) => c.addEventListener('change', hitung));

    barisAwal.forEach((b) => tambahBaris(b.id, b.jumlah));
    hitung();
</script>
@endpush

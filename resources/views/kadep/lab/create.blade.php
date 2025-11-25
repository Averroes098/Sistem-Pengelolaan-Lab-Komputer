@extends('layouts.kadep')

@section('content')
<h1>Tambah Peminjaman Lab</h1>

<form action="{{ route('kadep.peminjaman.lab.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="lab_id" class="form-label">Laboratorium</label>
        <select name="lab_id" id="lab_id" class="form-control" required>
            <option value="">-- Pilih Laboratorium --</option>
            @foreach($laboratorium as $lab)
                <option value="{{ $lab->id }}">{{ $lab->nama_lab }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
        <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="kembali" class="form-label">Tanggal Kembali</label>
        <input type="date" name="kembali" id="kembali" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('kadep.peminjaman.lab.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
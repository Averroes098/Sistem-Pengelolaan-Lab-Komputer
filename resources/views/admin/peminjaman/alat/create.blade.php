@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tambah Peminjaman Alat</h4>

        <form action="{{ route('admin.peminjaman.alat.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama User</label>
        <select name="user_id" class="form-control" required>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Alat</label>
        <select name="alat_id" class="form-control" required>
            @foreach($alats as $a)
                <option value="{{ $a->id }}">{{ $a->nama_alat }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Tanggal Pinjam</label>
        <input type="date" name="tgl_pinjam" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Tanggal Kembali</label>
        <input type="date" name="tgl_kembali" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Status Peminjaman</label>
        <select name="status_peminjaman" class="form-control">
            <option value="pending">Pending</option>
            <option value="dipinjam">Dipinjam</option>
            <option value="selesai">Selesai</option>
        </select>
    </div>

    <button class="btn btn-primary mt-3">Simpan</button>

    </form>
    </div>
</div>
@endsection
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
                    @foreach($alat as $a)
                        <option value="{{ $a->id }}">{{ $a->nama_alat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Menunggu">Menunggu</option>
                    <option value="Dipinjam">Dipinjam</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <button class="btn btn-primary mt-3">Simpan</button>

        </form>
    </div>
</div>
@endsection
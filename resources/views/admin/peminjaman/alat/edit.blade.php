@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Peminjaman</h4>

        <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama User</label>
                <select name="user_id" class="form-control">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $peminjaman->user_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Alat</label>
                <select name="alat_id" class="form-control">
                    @foreach($alat as $a)
                        <option value="{{ $a->id }}" {{ $peminjaman->alat_id == $a->id ? 'selected' : '' }}>
                            {{ $a->nama_alat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="{{ $peminjaman->tanggal_pinjam }}">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Menunggu" {{ $peminjaman->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Dipinjam" {{ $peminjaman->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Selesai" {{ $peminjaman->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <button class="btn btn-primary mt-3">Update</button>

        </form>
    </div>
</div>
@endsection
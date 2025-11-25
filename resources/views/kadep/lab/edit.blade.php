@extends('layouts.kadep')

@section('content')
<h1>Edit Peminjaman Lab</h1>

<form action="{{ route('kadep.peminjaman.lab.update', $peminjaman->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="lab_id" class="form-label">Laboratorium</label>
        <select name="lab_id" id="lab_id" class="form-control" required>
            @foreach($laboratorium as $lab)
                <option value="{{ $lab->id }}" {{ $peminjaman->lab_id == $lab->id ? 'selected' : '' }}>
                    {{ $lab->nama_lab }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
        <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" value="{{ $peminjaman->tgl_pinjam }}" required>
    </div>

    <div class="mb-3">
        <label for="kembali" class="form-label">Tanggal Kembali</label>
        <input type="date" name="kembali" id="kembali" class="form-control" value="{{ $peminjaman->tgl_kembali }}" required>
    </div>

    <div class="mb-3">
        <label for="status_peminjaman" class="form-label">Status Peminjaman</label>
        <select name="status_peminjaman" id="status_peminjaman" class="form-control" required>
            <option value="pending" {{ $peminjaman->status_peminjaman == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="disetujui" {{ $peminjaman->status_peminjaman == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ $peminjaman->status_peminjaman == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="status_pengembalian" class="form-label">Status Pengembalian</label>
        <select name="status_pengembalian" id="status_pengembalian" class="form-control" required>
            <option value="belum dikembalikan" {{ $peminjaman->status_pengembalian == 'belum dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
            <option value="sudah dikembalikan" {{ $peminjaman->status_pengembalian == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('kadep.peminjaman.lab.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
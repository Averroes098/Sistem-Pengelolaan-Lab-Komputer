@extends('layouts.main')
@section('title', 'Edit Data Peminjaman')

@section('content')
<div class="content-wrapper">

  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Edit Data Peminjaman</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.peminjaman.index') }}">Data Peminjaman</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Form Edit Data</h4>

      <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- STATUS (nama sesuai DB) -->
        <div class="form-group">
          <label for="status_peminjaman">Status Peminjaman</label>
          <select name="status_peminjaman" id="status_peminjaman" class="form-control" required>
            <option disabled>Pilih status peminjaman</option>
            <option value="pending" {{ $peminjaman->status_peminjaman == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="disetujui" {{ $peminjaman->status_peminjaman == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ $peminjaman->status_peminjaman == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>

        <!-- STATUS PENGEMBALIAN (nama sesuai DB) -->
        <div class="form-group">
          <label for="status_pengembalian">Status Pengembalian</label>
          <select name="status_pengembalian" id="status_pengembalian" class="form-control" required>
            <option disabled>Pilih status pengembalian</option>
            <option value="belum dikembalikan" {{ $peminjaman->status_pengembalian == 'belum dikembalikan' ? 'selected' : '' }}>Belum dikembalikan</option>
            <option value="sudah dikembalikan" {{ $peminjaman->status_pengembalian == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah dikembalikan</option>
          </select>
        </div>

        <button class="btn btn-success mr-2">Simpan</button>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-light">Cancel</a>
      </form>

    </div>
  </div>
</div>
@endsection
@extends('layouts.main')
@section('title', 'Edit Data Peminjaman')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Edit Data Peminjaman</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Data Peminjaman</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Data Peminjaman</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- Page Title Header Ends-->
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Form Edit Data</h4>
      <p class="card-description"> Pastikan anda memasuki inputan dengan benar </p>
      <form class="forms-sample" method="POST" action="{{ route('peminjaman.update',$peminjaman->id) }}">
        @csrf
        <div class="form-group">
          <label for="status_peminjaman">Status Peminjaman</label>
          <select class="form-control" id="status_peminjaman" name="status_peminjaman" required>
            <option value="" disabled>Ubah status peminjaman</option>
            <option value="disetujui" {{ old('status_peminjaman', $peminjaman->status_peminjaman) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ old('status_peminjaman', $peminjaman->status_peminjaman) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
        <div class="form-group">
          <label for="status_pengembalian">Status Pengembalian</label>
          <select class="form-control" id="status_pengembalian" name="status_pengembalian" required>
            <option value="" disabled>Ubah status peminjaman</option>
            <option value="belum dikembalikan" {{ old('status_pengembalian', $peminjaman->status_pengembalian) == 'belum dikembalikan' ? 'selected' : '' }}>Belum dikembalikan</option>
            <option value="sudah dikembalikan" {{ old('status_pengembalian', $peminjaman->status_pengembalian) == 'sudah dikembalikan' ? 'selected' : '' }}>Sudah dikembalikan</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success mr-2">Simpan</button>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection

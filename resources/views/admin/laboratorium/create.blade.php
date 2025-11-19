@extends('layouts.main')
@section('title', 'Tambah Data Laboratorium')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Tambah Data Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('laboratorium.index') }}">Data Laboratorium</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tambah Data Laboratorium</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- Page Title Header Ends-->
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Form Tambah Data</h4>
      <p class="card-description"> Pastikan anda memasuki inputan dengan benar </p>
      <form class="forms-sample" method="POST" action="{{ route('laboratorium.store') }}">
        @CSRF
        <div class="form-group">
          <label for="nama">Nama Laboratorium</label>
          <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Laboratorium">
        </div>
        <div class="form-group">
          <label for="status">Status Laboratorium</label>
          <select class="form-control" id="status" name="status">
            <option value="" disabled selected>Pilih status...</option>
            <option value=1>Tersedia</option>
            <option value=0>Tidak Tersedia</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success mr-2">Tambah</button>
        <button class="btn btn-light">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
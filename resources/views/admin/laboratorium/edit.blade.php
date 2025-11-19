@extends('layouts.main')
@section('title', 'Edit Data Laboratorium')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Edit Data Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('laboratorium.index') }}">Data Laboratorium</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Data Laboratorium</li>
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
      <form class="forms-sample" method="POST" action="{{ route('laboratorium.update',$laboratorium->id) }}">
        @csrf
        <div class="form-group">
          <label for="nama">Nama Laboratorium</label>
          <input 
            type="text" 
            class="form-control" 
            id="nama" 
            name="nama" 
            placeholder="Nama Laboratorium" 
            value="{{ old('nama', $laboratorium->nama) }}" 
            required>
        </div>
        <div class="form-group">
          <label for="status">Status Laboratorium</label>
          <select class="form-control" id="status" name="status" required>
            <option value="" disabled>Pilih status...</option>
            <option value="1" {{ old('status', $laboratorium->status) == 1 ? 'selected' : '' }}>Tersedia</option>
            <option value="0" {{ old('status', $laboratorium->status) == 0 ? 'selected' : '' }}>Tidak Tersedia</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success mr-2">Simpan</button>
        <a href="{{ route('laboratorium.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection

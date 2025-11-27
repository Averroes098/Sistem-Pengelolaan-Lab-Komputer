@extends('layouts.main')
@section('title', 'Edit Data Laboratorium')

@section('content')
<div class="content-wrapper">

  <!-- Page Title Header -->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Edit Data Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item">
                <a href="{{ route('admin.laboratorium.index') }}">Data Laboratorium</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Form Edit Data Laboratorium</h4>
      <p class="card-description">Pastikan semua input diisi dengan benar</p>

      <!-- FORM -->
      <form method="POST" action="{{ route('admin.laboratorium.update', $laboratorium->id) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')  {{-- Penting untuk mengubah POST menjadi PUT --}}

        <!-- Nama Lab -->
        <div class="form-group">
          <label for="nama">Nama Laboratorium</label>
          <input 
            type="text" 
            id="nama" 
            name="nama" 
            class="form-control"
            placeholder="Nama Laboratorium"
            value="{{ old('nama', $laboratorium->nama_lab) }}"
            required>
        </div>

        <!-- Status -->
        <div class="form-group">
          <label for="status">Status Laboratorium</label>
          <select id="status" name="status" class="form-control" required>
              <option disabled>Pilih status...</option>

              <option value="tersedia" 
                  {{ old('status', $laboratorium->status) == 'tersedia' ? 'selected' : '' }}>
                  Tersedia
              </option>

              <option value="terpakai" 
                  {{ old('status', $laboratorium->status) == 'terpakai' ? 'selected' : '' }}>
                  Terpakai
              </option>

              <option value="maintenance" 
                  {{ old('status', $laboratorium->status) == 'maintenance' ? 'selected' : '' }}>
                  Maintenance
              </option>
          </select>
        </div>

        <!-- Foto -->
        <div class="form-group">
          <label for="foto">Foto Laboratorium</label>
          <input type="file" class="form-control-file" id="foto" name="foto">
          @if($laboratorium->foto)
              <img src="{{ asset($laboratorium->foto) }}" alt="{{ $laboratorium->nama_lab }}" class="img-thumbnail mt-2" width="150">
          @endif
        </div>

        <button type="submit" class="btn btn-success mr-2">Simpan</button>
        <a href="{{ route('admin.laboratorium.index') }}" class="btn btn-light">Cancel</a>
      </form>

    </div>
  </div>

</div>
@endsection
@extends('layouts.main')
@section('title', 'Data Alat')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Data Alat</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kadep.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Alat</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif

  @if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif

  <!-- Page Title Header Ends-->
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between">
        <h4 class="card-title mb-0">Data Alat</h4>
        <a href="{{ route('kadep.alat.create') }}" class="btn btn-primary btn-sm">Tambah Alat</a>
      </div>
      <p>Berikut adalah data alat yang tersedia.</p>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Kode Alat</th>
              <th>Gambar</th>
              <th>Nama Alat</th>
              <th>Kategori</th>
              <th>Jumlah</th>
              <th>Kondisi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($alat as $item)
            <tr>
              <td>{{ $item->kode_alat }}</td>
              <td>
                @if($item->gambar)
                  <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_alat }}" class="img-thumbnail" width="100">
                @else
                  No Image
                @endif
              </td>
              <td>{{ $item->nama_alat }}</td>
              <td>{{ $item->kategori }}</td>
              <td>{{ $item->jumlah }}</td>
              <td>{{ $item->kondisi }}</td>
              <td>
                <a href="{{ route('kadep.alat.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                <form action="{{ route('kadep.alat.destroy', $item->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
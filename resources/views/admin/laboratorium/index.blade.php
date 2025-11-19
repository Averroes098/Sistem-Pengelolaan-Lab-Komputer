@extends('layouts.main')
@section('title', 'Data Laboratorium')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Data Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data Laboratorium</li>
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
        <h4 class="card-title mb-0">Data Laboratorium</h4>
        <a class="btn-sm btn-primary btn-rounded" href="{{ route('laboratorium.create') }}"><small>Tambah Data</small></a>
      </div>
      <p>Berikut adalah beberapa data laboratorim yang tercatat.</p>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Lab ID</th>
              <th>Nama</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $d)
              <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->nama }}</td>
                <td><a class="btn-sm btn-{{ $d->status ? 'success':'danger' }} btn-rounded">{{ $d->status ? 'Tersedia':'Tidak Tersedia' }}</a></td>
                <td>
                  <a href="{{ route('laboratorium.edit',$d->id) }}" class="btn-sm btn-info btn-rounded">Edit</a>
                  <a href="{{ route('laboratorium.destroy',$d->id) }}" class="btn-sm btn-danger btn-rounded">Delete</a>
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
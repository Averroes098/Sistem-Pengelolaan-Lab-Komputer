@extends('layouts.main')
@section('title', 'Data Peminjaman')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Data Peminjaman</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data Peminjaman</li>
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
        <h4 class="card-title mb-0">Data Peminjaman</h4>
      </div>
      <p>Berikut adalah beberapa data laboratorim yang tercatat.</p>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Lab ID</th>
              <th>Nama Peminjam</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status Peminjaman</th>
              <th>Status Pengembalian</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($peminjaman as $d)
              <tr>
                <td>{{ $d->lab_id }}</td>
                <td>{{ $d->user->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($d->tgl_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($d->kembali)->format('d M Y') }}</td>
                <td>
                  @if ($d->status_peminjaman == 'pending')
                    <a class="btn btn-sm btn-warning btn-rounded">Pending</a>
                  @elseif($d->status_peminjaman == 'disetujui')
                    <a class="btn btn-sm btn-success btn-rounded">Disetujui</a>
                  @elseif($d->status_peminjaman == 'ditolak')
                    <a class="btn btn-sm btn-danger btn-rounded">Ditolak</a>
                  @endif
                </td>
                <td>
                  @if ($d->status_pengembalian == 'belum dikembalikan')
                    <a class="btn btn-sm btn-warning btn-rounded">Belum Dikembalikan</a>
                  @elseif($d->status_pengembalian == 'sudah dikembalikan')
                    <a class="btn btn-sm btn-success btn-rounded">Sudah Dikembalikan</a>
                  @endif
                </td>
                <td>
                  <a href="{{ route('peminjaman.edit',$d->id) }}" class="btn-sm btn-info btn-rounded">Edit</a>
                  <a href="{{ route('peminjaman.destroy',$d->id) }}" class="btn-sm btn-danger btn-rounded">Delete</a>
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
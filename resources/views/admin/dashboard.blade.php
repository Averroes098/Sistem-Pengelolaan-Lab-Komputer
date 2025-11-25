@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Dashboard</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- LABORATORIUM -->
    <div class="col-md-8 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h4 class="card-title mb-0">Data Laboratorium</h4>
            <a href="{{ route('admin.laboratorium.index') }}"><small>Show All</small></a>
          </div>
          <p>Berikut adalah beberapa data laboratorim yang tercatat.</p>

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Lab ID</th>
                  <th>Nama</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_lab as $dl)
                  <tr>
                    <td>{{ $dl->id }}</td>
                    <td>{{ $dl->nama_lab }}</td>

<td>
    @if ($dl->status == 'tersedia')
        <span class="badge badge-success">Tersedia</span>
    @elseif ($dl->status == 'terpakai')
        <span class="badge badge-warning">Terpakai</span>
    @elseif ($dl->status == 'maintenance')
        <span class="badge badge-danger">Maintenance</span>
    @endif
</td>

                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- PEMINJAMAN TERBARU -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-0">Peminjaman Terbaru</h4>

          @forelse ($data_peminjaman as $dp)
          <div class="d-flex py-2 border-bottom">
            <div class="wrapper">
              <small class="text-muted">
                {{ \Carbon\Carbon::parse($dp->tgl_pinjam)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($dp->tgl_kembali)->format('d M Y') }}
              </small>

              <h6 class="font-weight-semibold text-gray mb-1">
                {{ $dp->user?->nama ?? 'User Tidak Dikenal' }}
              </h6>

              <p class="font-sm text-gray">Lab: {{ $dp->laboratorium?->nama_lab ?? 'Tidak ditemukan' }}</p>

              <p class="font-sm text-gray">
                {{ $dp->alat?->nama_alat ?? 'Alat tidak ditemukan' }}
              </p>
            </div>
          </div>
          @empty
          <p class="text-muted text-center py-3">Belum ada data peminjaman</p>
          @endforelse

          <a class="d-block mt-3" href="{{ route('admin.peminjaman.lab.index') }}">Show all</a>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
@extends('layouts.main')
@section('title', 'Data Peminjaman Alat')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Data Peminjaman Alat</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Peminjaman Alat</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
        <h4 class="card-title mb-0">Data Peminjaman Alat</h4>
        <a href="{{ route('admin.peminjaman.alat.create') }}" class="btn btn-primary btn-rounded">+ Tambah Peminjaman Alat</a>
      </div>
      <p>Berikut adalah daftar peminjaman alat yang tercatat.</p>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Laboratorium</th>
              <th>Nama Peminjam</th>
              <th>Alat</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
            @foreach($peminjaman as $p)
              <tr>
                <td>{{ $p->laboratorium->nama_lab ?? '-' }}</td>
                <td>{{ $p->user->nama ?? $p->user->name }}</td>
                <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tgl_pinjam ?? $p->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tgl_kembali ?? $p->tanggal_kembali)->format('d M Y') }}</td>
                <td>
                  @if ($p->status_peminjaman == 'pending')
                    <span class="btn btn-sm btn-warning btn-rounded">Pending</span>
                  @elseif ($p->status_peminjaman == 'disetujui')
                    <span class="btn btn-sm btn-success btn-rounded">Disetujui</span>
                  @elseif ($p->status_peminjaman == 'ditolak')
                    <span class="btn btn-sm btn-danger btn-rounded">Ditolak</span>
                  @else
                    <span class="btn btn-sm btn-secondary btn-rounded">{{ $p->status ?? '-' }}</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.peminjaman.alat.edit', $p->id) }}" class="btn btn-sm btn-warning btn-rounded">Edit</a>
                  <form action="{{ route('admin.peminjaman.alat.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger btn-rounded" onclick="return confirm('Hapus data ini?')">Hapus</button>
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
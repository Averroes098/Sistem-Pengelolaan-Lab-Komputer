@extends('layouts.main')
@section('title', 'User Page')

@section('style')
    
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Selamat Datang, {{ auth()->user()->nama }}</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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

  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <!-- Page Title Header Ends-->
  <div class="row">
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-2">Data User</h4>
          <p class="card-text mb-1"><strong>NIM:</strong> {{ auth()->user()->nim }}</p>
          <p class="card-text mb-1"><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
          <p class="card-text mb-1"><strong>No Telepon:</strong> {{ auth()->user()->no_telp }}</p>
          <p class="card-text mb-1"><strong>Jenis Kelamin:</strong> 
          @if (auth()->user()->jenis_kelamin == 'L')
            Laki-laki
          @else
            Perempuan
          @endif
          </p>
          <p class="card-text"><strong>Email:</strong> {{ auth()->user()->email }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-8 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h4 class="mb-2">Status Peminjaman Laboratorium</h4>
          </div>
          <p>Anda dapat melihat status peminjaman laboratorium anda disini.</p>
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
                </tr>
              </thead>
              <tbody>
                @forelse ($data as $d)
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
                  </tr>
                @empty
                <div class="alert alert-secondary">
                  Anda belum melakukan peminjaman apapun.
                </div>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <h5 class="mb-2">Form Peminjaman Laboratorium</h5>
            <form action="{{ route('user.pinjam') }}" method="POST">
              @csrf
                <!-- Input Tanggal Pinjam -->
                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tgl_pinjam" required>
                </div>

                <!-- Input Tanggal Kembali -->
                <div class="mb-3">
                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                    <input type="date" class="form-control" id="tanggal_kembali" name="tgl_kembali" required>
                </div>

                <!-- Pilihan Laboratorium -->
                <div class="mb-3">
                    <label for="laboratorium" class="form-label">Laboratorium</label>
                    <select class="form-control" id="laboratorium" name="lab_id" required>
                        <option selected disabled>Pilih laboratorium yang tersedia</option>
                        @foreach ($available_lab as $al)
                          <option value="{{ $al->id }}">{{ $al->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
            </form>
        </div>
    </div>
    </div>
  </div>
</div>
@endsection
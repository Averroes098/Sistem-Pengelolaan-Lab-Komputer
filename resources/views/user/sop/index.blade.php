@extends('layouts.main')
@section('title', 'SOP Laboratorium')

@section('content')
<div class="content-wrapper">
  <!-- Page Title Header Starts-->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">SOP Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">SOP</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- Page Title Header Ends-->
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-0">Daftar SOP</h4>
      <p>Berikut adalah daftar Standard Operating Procedure (SOP) yang tersedia.</p>

      <div class="list-group">
        @forelse ($sops as $sop)
          <a href="{{ asset('storage/' . $sop->file_path) }}" target="_blank" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">{{ $sop->judul }}</h5>
              <small>{{ $sop->created_at->format('d M Y') }}</small>
            </div>
            <p class="mb-1">{{ $sop->deskripsi }}</p>
            <small>Laboratorium: {{ $sop->laboratorium->nama_lab ?? 'Semua Lab' }}</small>
          </a>
        @empty
          <div class="alert alert-info">
            Belum ada SOP yang diunggah.
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

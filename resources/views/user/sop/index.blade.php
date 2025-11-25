@extends('layouts.main')
@section('title', 'SOP Laboratorium')

@section('content')
<div class="content-wrapper">
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">SOP Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">SOP</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="row">
    @forelse ($sops as $sop)
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $sop->judul }}</h5>

            <div class="mb-2">
              <span class="badge bg-primary">Laboratorium: {{ $sop->laboratorium->nama_lab ?? 'Semua Lab' }}</span>
              <span class="badge bg-secondary">{{ $sop->created_at->format('d M Y') }}</span>
              @if(!empty($sop->kategori))
                <span class="badge bg-info">{{ $sop->kategori }}</span>
              @endif
            </div>

            <div class="card-text mb-3" style="max-height: 120px; overflow-y: auto;">
              {{ $sop->deskripsi }}
            </div>

            @if($sop->file_path)
              <a href="{{ asset('storage/' . $sop->file_path) }}" target="_blank" class="btn btn-sm btn-primary mt-auto">
                <i class="mdi mdi-file-document-outline"></i> Lihat / Download SOP
              </a>
            @else
              <span class="text-muted mt-auto">File SOP belum tersedia</span>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">
          Belum ada SOP yang diunggah.
        </div>
      </div>
    @endforelse
  </div>
</div>
@endsection
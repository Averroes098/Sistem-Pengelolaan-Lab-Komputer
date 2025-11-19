@extends('layouts.main')
@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="content-wrapper">
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h3 class="page-title text-primary">Selamat Datang, {{ auth()->user()->nama }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (auth()->check() && ( (isset(auth()->user()->level) && auth()->user()->level === 'user') || (isset(auth()->user()->role) && auth()->user()->role === 'user') ) && !auth()->user()->is_profile_complete)
        @php
            $user = auth()->user();
            $missing = [];
            if (empty($user->program_studi)) $missing[] = 'Program Studi';
            if (empty($user->angkatan)) $missing[] = 'Angkatan';
            if (empty($user->alamat)) $missing[] = 'Alamat';
            $filled = 3 - count($missing);
            $completion = ($filled / 3) * 100;
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning mb-4 d-flex flex-column" role="alert">
                    <h5 class="d-flex align-items-center">
                        <i class="mdi mdi-alert-circle-outline mr-2 display-4"></i> Profil Belum Lengkap
                    </h5>
                    <p class="mb-2">Lengkapi data berikut agar bisa meminjam laboratorium:</p>
                    
                    <div class="progress mb-2" style="height: 15px;">
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $completion }}%;" aria-valuenow="{{ round($completion) }}" aria-valuemin="0" aria-valuemax="100">
                             {{ round($completion) }}%
                        </div>
                    </div>

                    @if (!empty($missing))
                        <p class="text-dark mt-1 mb-2">
                            Data kosong: <strong class="text-danger">{{ implode(', ', $missing) }}</strong>
                        </p>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-sm mt-2" style="width: fit-content;">
                        <i class="mdi mdi-account-edit"></i> Lengkapi Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle mr-2"></i> {{ session('success') }}
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

    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ asset('images/faces/face8.jpg') }}" alt="Profile" class="img-lg rounded-circle mb-3">
                    <h4 class="mb-0">{{ auth()->user()->nama }}</h4>
                    <p class="text-muted">{{ auth()->user()->nim }}</p>
                    
                    <div class="text-left mt-4">
                        <p class="clearfix">
                            <span class="float-left font-weight-bold">Prodi</span>
                            <span class="float-right text-muted">{{ auth()->user()->program_studi ?? '-' }}</span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left font-weight-bold">Angkatan</span>
                            <span class="float-right text-muted">{{ auth()->user()->angkatan ?? '-' }}</span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left font-weight-bold">Kontak</span>
                            <span class="float-right text-muted">{{ auth()->user()->email }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 grid-margin">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-1">Pilih Laboratorium</h4>
                    <p class="text-muted mb-4">Silakan pilih laboratorium yang tersedia untuk dipinjam.</p>

                    <div class="row">
                        {{-- Menggunakan variabel $available_lab yang sudah ada di controller anda --}}
                        @forelse ($available_lab as $al) 
                        <div class="col-md-6 mb-4">
                            <div class="card border rounded shadow-sm h-100 hover-scale">
                                <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 120px;">
                                    <i class="mdi mdi-desktop-mac display-4"></i>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $al->nama_lab }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="mdi mdi-map-marker text-danger"></i> {{ $al->lokasi ?? 'Gedung A' }} <br>
                                        <i class="mdi mdi-monitor text-info"></i> Kapasitas: {{ $al->kapasitas ?? '30' }} Unit
                                    </p>
                                    
                                    @if (auth()->user()->is_profile_complete)
                                        <a href="{{ route('peminjaman.create', $al->id) }}" class="btn btn-primary btn-block btn-sm">
                                            Pinjam Lab Ini <i class="mdi mdi-arrow-right ml-1"></i>
                                        </a>
                                    @else
                                        <button disabled class="btn btn-secondary btn-block btn-sm" title="Lengkapi profil dulu">
                                            Lengkapi Profil
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">Tidak ada laboratorium yang tersedia saat ini.</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Riwayat Pengajuan Saya</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Laboratorium</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Status</th>
                                    <th>Pengembalian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $d)
                                    <tr>
                                        <td class="font-weight-bold">{{ $d->laboratorium->nama_lab ?? $d->lab_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->tgl_pinjam)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->kembali)->format('d M Y') }}</td>
                                        <td>
                                            @if ($d->status_peminjaman == 'pending')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($d->status_peminjaman == 'disetujui')
                                                <span class="badge badge-success">Disetujui</span>
                                            @elseif($d->status_peminjaman == 'ditolak')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($d->status_pengembalian == 'belum dikembalikan')
                                                <span class="text-muted small"><i class="mdi mdi-close-circle text-danger"></i> Belum</span>
                                            @else
                                                <span class="text-muted small"><i class="mdi mdi-check-circle text-success"></i> Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="mdi mdi-file-document-box-outline display-4 d-block mb-2"></i>
                                            Belum ada riwayat peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($data, 'links'))
                        <div class="mt-3">
                            {{ $data->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
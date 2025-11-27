@extends('layouts.main')

@section('title', 'Form Peminjaman Lab')

@section('content')
<div class="content-wrapper">
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h3 class="page-title text-primary">
                    <i class="mdi mdi-plus-circle mr-2"></i>Form Peminjaman Lab
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Peminjaman Lab</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="mdi mdi-form-textarea mr-2"></i>Formulir Peminjaman Laboratorium
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">
                                <i class="mdi mdi-alert-circle mr-2"></i>Validasi Error
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.storeUser') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Hidden field untuk lab_id -->
                        <input type="hidden" name="laboratorium_id" value="{{ $lab->id }}">

                        <!-- Info Lab -->
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Laboratorium Tujuan</label>
                            <div class="alert alert-info border-0">
                                <strong>
                                    <i class="mdi mdi-information mr-2"></i>{{ $lab->nama_lab ?? 'N/A' }}
                                </strong><br>
                                <small class="text-muted">
                                    📍 Lokasi: {{ $lab->lokasi ?? 'Gedung A' }}<br>
                                    👥 Kapasitas: {{ $lab->kapasitas ?? '30' }} Unit
                                </small>
                            </div>
                        </div>

                        <!-- Info Peminjam -->
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Nama Peminjam</label>
                            <input type="text" class="form-control bg-light" 
                                   value="{{ auth()->user()->nama ?? auth()->user()->name }}" readonly>
                            <small class="text-muted">Akun Anda terdaftar</small>
                        </div>

                        <hr class="my-4">

                        <!-- Tanggal Peminjaman -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pinjam" class="font-weight-bold text-dark">
                                        <i class="mdi mdi-calendar mr-2"></i>Tanggal Peminjaman
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                           id="tanggal_pinjam" name="tanggal_pinjam" 
                                           value="{{ old('tanggal_pinjam') }}"
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_pinjam')
                                        <small class="text-danger d-block mt-2">
                                            <i class="mdi mdi-alert-circle mr-1"></i>{{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_kembali" class="font-weight-bold text-dark">
                                        <i class="mdi mdi-calendar-check mr-2"></i>Tanggal Kembali
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                                           id="tanggal_kembali" name="tanggal_kembali" 
                                           value="{{ old('tanggal_kembali') }}"
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_kembali')
                                        <small class="text-danger d-block mt-2">
                                            <i class="mdi mdi-alert-circle mr-1"></i>{{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Jam Mulai dan Selesai -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai" class="font-weight-bold text-dark">
                                        <i class="mdi mdi-clock-start mr-2"></i>Jam Mulai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                           id="jam_mulai" name="jam_mulai" 
                                           value="{{ old('jam_mulai') }}" required>
                                    @error('jam_mulai')
                                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_selesai" class="font-weight-bold text-dark">
                                        <i class="mdi mdi-clock-end mr-2"></i>Jam Selesai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                           id="jam_selesai" name="jam_selesai" 
                                           value="{{ old('jam_selesai') }}" required>
                                    @error('jam_selesai')
                                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="form-group">
                            <label for="keperluan" class="font-weight-bold text-dark">
                                <i class="mdi mdi-note-text mr-2"></i>Keperluan / Keterangan
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                      id="keperluan" name="keperluan" rows="4" 
                                      placeholder="Contoh: Praktikum Pemrograman Web, Rapat Kelompok, dll"
                                      required>{{ old('keperluan') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="mdi mdi-information-outline mr-1"></i>Jelaskan tujuan peminjaman lab ini
                            </small>
                            @error('keperluan')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Info Box -->
                        <div class="alert alert-light border-left-warning pl-3">
                            <small class="text-muted">
                                <i class="mdi mdi-lightbulb-on-outline text-warning mr-2"></i>
                                <strong>Catatan:</strong> Pengajuan peminjaman akan diperiksa oleh staff lab. 
                                Anda akan menerima notifikasi setelah disetujui atau ditolak.
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard.user') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-check-circle mr-2"></i>Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-md-4 grid-margin">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-primary font-weight-bold">
                        <i class="mdi mdi-help-circle mr-2"></i>Panduan Peminjaman
                    </h6>
                    <div class="text-muted small">
                        <p class="mb-3">
                            <strong>📋 Langkah-langkah:</strong>
                        </p>
                        <ol class="pl-3">
                            <li class="mb-2">Pilih tanggal peminjaman</li>
                            <li class="mb-2">Tentukan jam mulai dan selesai</li>
                            <li class="mb-2">Jelaskan tujuan peminjaman</li>
                            <li class="mb-2">Klik tombol "Ajukan Peminjaman"</li>
                            <li class="mb-2">Tunggu persetujuan dari staff lab</li>
                        </ol>
                        
                        <hr>
                        
                        <p class="mb-2">
                            <strong>⏰ Jam Operasional:</strong><br>
                            Senin - Jumat: 08:00 - 16:00<br>
                            Sabtu - Minggu: Tutup
                        </p>

                        <hr>

                        <p class="mb-0">
                            <strong>❓ Pertanyaan?</strong><br>
                            <small>Hubungi staff lab melalui:<br>
                            📞 Telp: (021) XXX-XXXX<br>
                            📧 Email: lab@kampus.ac.id
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .btn {
        border-radius: 0.375rem;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

<script>
    // Validasi form
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.querySelectorAll('.needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // Set minimum date ke hari ini
    document.getElementById('tanggal_pinjam').min = new Date().toISOString().split('T')[0];

    // Validasi jam selesai harus lebih besar dari jam mulai
    document.getElementById('jam_selesai').addEventListener('change', function() {
        var jamMulai = document.getElementById('jam_mulai').value;
        var jamSelesai = this.value;
        if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
            this.setCustomValidity('Jam selesai harus lebih besar dari jam mulai');
        } else {
            this.setCustomValidity('');
        }
    });

    document.getElementById('jam_mulai').addEventListener('change', function() {
        document.getElementById('jam_selesai').setCustomValidity('');
    });

    // Set minimum date for tanggal_kembali based on tanggal_pinjam
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        document.getElementById('tanggal_kembali').min = this.value;
    });
</script>

@extends('layouts.main')
@section('title', 'Lengkapi Data Profil')

@section('content')
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title">⚠️ Lengkapi Data Profil Anda</h3>
    <p class="text-muted">Anda harus melengkapi data profil sebelum dapat mengakses sistem</p>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Data Wajib Diisi</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <!-- NIM (Wajib) -->
        <div class="form-group mb-3">
          <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
          <input type="text" 
                 name="nim" 
                 id="nim" 
                 class="form-control @error('nim') is-invalid @enderror"
                 value="{{ old('nim', $user->nim) }}" 
                 required
                 placeholder="Contoh: 2301020006">
          @error('nim')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- No Telp (Wajib) -->
        <div class="form-group mb-3">
          <label for="no_telp" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
          <input type="text" 
                 name="no_telp" 
                 id="no_telp" 
                 class="form-control @error('no_telp') is-invalid @enderror"
                 value="{{ old('no_telp', $user->no_telp) }}" 
                 required
                 placeholder="Contoh: 081234567890">
          @error('no_telp')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Jenis Kelamin (Wajib) -->
        <div class="form-group mb-3">
          <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
          <select name="jenis_kelamin" 
                  id="jenis_kelamin" 
                  class="form-select @error('jenis_kelamin') is-invalid @enderror"
                  required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
          </select>
          @error('jenis_kelamin')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <hr class="my-4">

        <h5 class="card-title">Data Tambahan (Opsional)</h5>

        <!-- Program Studi (Opsional) -->
        <div class="form-group mb-3">
          <label for="program_studi" class="form-label">Program Studi</label>
          <input type="text" 
                 name="program_studi" 
                 id="program_studi" 
                 class="form-control @error('program_studi') is-invalid @enderror"
                 value="{{ old('program_studi', $user->program_studi) }}"
                 placeholder="Contoh: Teknik Informatika">
          @error('program_studi')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Angkatan (Opsional) -->
        <div class="form-group mb-3">
          <label for="angkatan" class="form-label">Angkatan</label>
          <input type="text" 
                 name="angkatan" 
                 id="angkatan" 
                 class="form-control @error('angkatan') is-invalid @enderror"
                 value="{{ old('angkatan', $user->angkatan) }}"
                 placeholder="Contoh: 2023">
          @error('angkatan')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Alamat (Opsional) -->
        <div class="form-group mb-3">
          <label for="alamat" class="form-label">Alamat</label>
          <textarea name="alamat" 
                    id="alamat" 
                    class="form-control @error('alamat') is-invalid @enderror"
                    rows="3"
                    placeholder="Masukkan alamat lengkap Anda">{{ old('alamat', $user->alamat) }}</textarea>
          @error('alamat')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mt-4">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-check"></i> Lengkapi Data
          </button>
          <a href="{{ route('logout') }}" class="btn btn-outline-secondary btn-lg"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
          </a>
        </div>
      </form>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </div>
</div>
@endsection

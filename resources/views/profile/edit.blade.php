@extends('layouts.main')
@section('title', 'Edit Profil')

@section('content')
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title">Lengkapi Data Profil</h3>
  </div>

  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
          <label for="prodi">Program Studi</label>
          <input type="text" name="prodi" id="prodi" class="form-control"
                 value="{{ old('prodi', $user->prodi) }}" required>
        </div>

        <div class="form-group">
          <label for="angkatan">Angkatan</label>
          <input type="number" name="angkatan" id="angkatan" class="form-control"
                 value="{{ old('angkatan', $user->angkatan) }}" required>
        </div>

        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        <a href="{{ route('user.index') }}" class="btn btn-light mt-3">Batal</a>
      </form>
    </div>
  </div>
</div>
@endsection
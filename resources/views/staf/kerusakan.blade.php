@extends('layouts.staf')

@section('title', 'Catat Kerusakan')

@section('content')
<div class="content-wrapper">

    <h3 class="mb-4"><i class="mdi mdi-alert-circle text-danger mr-2"></i>Catat Kerusakan Alat</h3>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validasi Error:</strong>
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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('staf.kerusakan.input') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="alat_id">Pilih Alat <span class="text-danger">*</span></label>
                    <select name="alat_id" id="alat_id" class="form-control @error('alat_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Alat --</option>
                        @forelse($alat as $item)
                            <option value="{{ $item->id }}" {{ old('alat_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_alat }} - {{ $item->nama_alat }}
                            </option>
                        @empty
                            <option value="" disabled>Tidak ada alat tersedia</option>
                        @endforelse
                    </select>
                    @error('alat_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="form-group mt-3">
                    <label for="keterangan">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                    <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                              rows="4" placeholder="Jelaskan jenis kerusakan, lokasi rusak, dll" required>{{ old('keterangan') }}</textarea>
                    <small class="text-muted">Jelaskan detail kerusakan dengan jelas</small>
                    @error('keterangan')<small class="text-danger d-block">{{ $message }}</small>@enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-content-save mr-2"></i>Catat Kerusakan
                    </button>
                    <a href="{{ route('staf.dashboard') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
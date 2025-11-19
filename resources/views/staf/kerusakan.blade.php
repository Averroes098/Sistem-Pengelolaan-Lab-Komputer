@extends('layouts.staf')

@section('title', 'Catat Kerusakan')

@section('content')
<div class="content-wrapper">

    <h3 class="mb-4">Catat Kerusakan Alat</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('staf.kerusakan.input') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Pilih Alat</label>
  <select name="alat_id" class="form-control">
    <option value="">-- Pilih Alat --</option>
    @foreach($alat as $item)
        <option value="{{ $item->id }}">{{ $item->kode_alat }} - {{ $item->nama_alat }}</option>
    @endforeach
</select>

                </div>

                <div class="form-group mt-3">
                    <label>Deskripsi Kerusakan</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-danger mt-3">Simpan</button>
            </form>

        </div>
    </div>

</div>
@endsection
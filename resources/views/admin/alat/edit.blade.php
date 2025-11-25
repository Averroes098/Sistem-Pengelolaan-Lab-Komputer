@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Alat</h4>

        <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kode Alat</label>
                <input type="text" name="kode_alat" class="form-control" value="{{ $alat->kode_alat }}" required>
            </div>

            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control" value="{{ $alat->nama_alat }}" required>
            </div>

            <div class="form-group">
                <label>Laboratorium</label>
                <select name="lab_id" class="form-control">
                    @foreach($laboratorium as $lab)
                        <option value="{{ $lab->id }}" {{ $alat->lab_id == $lab->id ? 'selected' : '' }}>
                            {{ $lab->nama_lab }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" class="form-control" value="{{ $alat->kategori }}" required>
            </div>

            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" class="form-control">
                    <option value="Baik" {{ $alat->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak" {{ $alat->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            <button class="btn btn-primary mt-3">Update</button>
        </form>

    </div>
</div>
@endsection
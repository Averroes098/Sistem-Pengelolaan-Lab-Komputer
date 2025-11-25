@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Alat</h4>
        <a href="{{ route('admin.alat.create') }}" class="btn btn-primary mb-3">+ Tambah Alat</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Lab</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alat as $a)
                <tr>
                    <td>{{ $a->kode_alat }}</td>
                    <td>{{ $a->nama_alat }}</td>
                    <td>{{ $a->laboratorium->nama_lab }}</td>
                    <td>{{ $a->kategori }}</td>
                    <td>{{ $a->kondisi }}</td>
                    <td>
                        <a href="{{ route('admin.alat.edit', $a->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.alat.destroy', $a->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
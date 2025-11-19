@extends('layout.app')

@section('content')
<h2>Daftar Alat Laboratorium</h2>
<a href="{{ route('alat.create') }}" class="btn btn-primary">Tambah Alat</a>

<table class="table mt-3">
  <thead>
    <tr>
      <th>Kode</th>
      <th>Nama Alat</th>
      <th>Kategori</th>
      <th>Jumlah</th>
      <th>Kondisi</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($alat as $a)
    <tr>
      <td>{{ $a->kode_alat }}</td>
      <td>{{ $a->nama_alat }}</td>
      <td>{{ $a->kategori }}</td>
      <td>{{ $a->jumlah }}</td>
      <td>{{ $a->kondisi }}</td>
      <td>
        <a href="{{ route('alat.edit', $a->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('alat.destroy', $a->id) }}" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection

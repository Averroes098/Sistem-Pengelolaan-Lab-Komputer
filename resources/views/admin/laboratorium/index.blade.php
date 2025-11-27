@extends('layouts.main')
@section('title', 'Data Laboratorium')

@section('content')
<div class="content-wrapper">

  <!-- Header -->
  <div class="row page-title-header">
    <div class="col-12">
      <div class="page-header">
        <h3 class="page-title">Data Laboratorium</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data Laboratorium</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- Alerts -->
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Table -->
  <div class="card">
    <div class="card-body">

      <div class="d-flex justify-content-between">
        <h4 class="card-title mb-0">Data Laboratorium</h4>
        <a class="btn-sm btn-primary btn-rounded" href="{{ route('admin.laboratorium.create') }}">
          <small>Tambah Data</small>
        </a>
      </div>

      <p>Berikut adalah beberapa data laboratorium yang tercatat.</p>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Lab ID</th>
              <th>Nama</th>
              <th>Foto</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>

<tbody>
    @foreach ($data as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->nama_lab }}</td>
            <td>
              @if($d->foto)
                <img src="{{ asset($d->foto) }}" alt="{{ $d->nama_lab }}" class="img-thumbnail" width="100">
              @else
                No Image
              @endif
            </td>

            <!-- STATUS ENUM -->
<td>
    @if ($d->status == 'tersedia')
        <span class="badge badge-success">Tersedia</span>
    @elseif ($d->status == 'terpakai')
        <span class="badge badge-warning">Terpakai</span>
    @elseif ($d->status == 'maintenance')
        <span class="badge badge-danger">Maintenance</span>
    @endif
</td>

            <td>
                <a href="{{ route('admin.laboratorium.edit', $d->id) }}"
                   class="btn-sm btn-info btn-rounded">
                   Edit
                </a>

                <form action="{{ route('admin.laboratorium.destroy', $d->id) }}"
                      method="POST"
                      style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn-sm btn-danger btn-rounded"
                            onclick="return confirm('Hapus data laboratorium ini?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
      </div>

    </div>
  </div>

</div>
@endsection
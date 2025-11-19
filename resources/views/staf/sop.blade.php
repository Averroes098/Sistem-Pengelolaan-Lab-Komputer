@extends('layouts.staf')

@section('title', 'Upload SOP')

@section('content')
<div class="content-wrapper">

    <h3 class="mb-4">Upload SOP Laboratorium</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('staf.sop.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Upload File SOP (PDF)</label>
                    <input type="file" name="sop" accept="application/pdf" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-info mt-3">Upload</button>
            </form>

        </div>
    </div>

</div>
@endsection
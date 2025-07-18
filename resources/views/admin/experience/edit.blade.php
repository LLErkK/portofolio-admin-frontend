@extends('layouts.admin')

@section('content')
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container py-4">
    <h2 class="mb-4">Edit Pengalaman Kerja</h2>

    <form method="POST" action="{{ route('admin.experience.update', $experience['id']) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <input type="text" name="company_name" class="form-control" placeholder="Nama Perusahaan" value="{{ $experience['company_name'] }}" required>
        </div>
        <div class="mb-3">
            <input type="text" name="position" class="form-control" placeholder="Posisi" value="{{ $experience['position'] }}" required>
        </div>
        <div class="mb-3">
            <input type="date" name="start_date" class="form-control" value="{{ $experience['start_date'] }}" required>
        </div>
        <div class="mb-3">
            <input type="date" name="end_date" class="form-control" value="{{ $experience['end_date'] }}">
        </div>
        <div class="mb-3">
            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi">{{ $experience['description'] }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.experience.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

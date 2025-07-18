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
    <h2 class="mb-4">Tambah Pengalaman Kerja</h2>

    <form method="POST" action="{{ route('admin.experience.store') }}" enctype="multipart/form-data" class="mb-5">
        @csrf
        <div class="mb-3">
            <input type="text" name="company_name" class="form-control" placeholder="Nama Perusahaan" required>
        </div>
        <div class="mb-3">
            <input type="text" name="position" class="form-control" placeholder="Posisi" required>
        </div>
        <div class="mb-3">
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <input type="date" name="end_date" class="form-control">
        </div>
        <div class="mb-3">
            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <hr>

    <h3 class="mb-3">Daftar Pengalaman Kerja</h3>
    <div class="row">
        @foreach ($experiences as $experience)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $experience['company_name'] }}</h5>
                        <p class="card-text">{{ $experience['position'] }}</p>
                        <p>{{ $experience['start_date'] }}</p>
                        <p>{{ $experience['end_date'] }}</p>
                        <p>{{ $experience['description'] }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                         <a href="{{ route('admin.experience.edit', $experience['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.experience.destroy', $experience['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengalaman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

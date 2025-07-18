@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Edit Sertifikat</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.certificate.update', $certificate['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POSt')

        <div class="mb-3">
            <label for="title">Judul Sertifikat</label>
            <input type="text" name="title" id="title" value="{{ $certificate['title'] }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="issuer">Penerbit</label>
            <input type="text" name="issuer" id="issuer" value="{{ $certificate['issuer'] }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="year">Tahun</label>
            <input type="number" name="year" id="year" value="{{ $certificate['year'] }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image">Gambar Sertifikat (opsional)</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($certificate['image'])
                <div class="mt-2">
                    <strong>Gambar Saat Ini:</strong><br>
                    <img src="{{ asset('storage/' . $certificate['image']) }}" alt="Certificate Image" width="150">
                </div>
            @endif
        </div>

        <button class="btn btn-primary" type="submit">Update Sertifikat</button>
        <a href="{{ route('admin.certificate.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

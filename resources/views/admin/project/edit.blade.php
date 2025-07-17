@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Edit Project</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ url('/admin/project/' . $project['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Proyek</label>
            <input type="text" class="form-control" name="name" value="{{ $project['name'] }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" rows="4">{{ $project['description'] }}</textarea>
        </div>

        <div class="mb-3">
            <label for="link" class="form-label">Link Proyek</label>
            <input type="url" class="form-control" name="link" value="{{ $project['link'] }}">
        </div>

        <div class="mb-3">
            <label for="tech_stack" class="form-label">Stack Teknologi</label>
            <input type="text" class="form-control" name="tech_stack" value="{{ $project['tech_stack'] }}">
        </div>

        <div class="mb-3">
        <label class="form-label">Gambar Lama:</label>
            <div class="d-flex flex-wrap gap-3">
@php
    $imageList = [];

    $raw = $project['images'] ?? null;

    if (is_array($raw)) {
        // Kalau array, mungkin berisi 1 elemen berupa string JSON
        if (count($raw) === 1 && is_string($raw[0])) {
            $jsonCandidate = $raw[0];

            // Jika mengandung "/storage/[..." hapus bagian prefix dulu
            if (str_starts_with($jsonCandidate, '/storage/[')) {
                $jsonCandidate = substr($jsonCandidate, strlen('/storage/'));
            }

            $decoded = json_decode($jsonCandidate, true);
            if (is_array($decoded)) {
                $imageList = $decoded;
            }
        } else {
            // array of path langsung
            $imageList = $raw;
        }
    } elseif (is_string($raw)) {
        // string json, atau string dengan prefix /storage/
        $jsonCandidate = $raw;

        if (str_starts_with($jsonCandidate, '/storage/[')) {
            $jsonCandidate = substr($jsonCandidate, strlen('/storage/'));
        }

        $decoded = json_decode($jsonCandidate, true);
        if (is_array($decoded)) {
            $imageList = $decoded;
        }
    }
@endphp

@foreach ($imageList as $image)
    
    <div class="position-relative">
        <img src="{{ env('API_BASE_URL') . '/storage/' . $image }}" width="150">
        <div class="form-check mt-1">
            <input class="form-check-input" type="checkbox" name="deleted_images[]" value="{{ $image }}">
            <label class="form-check-label">Hapus</label>
        </div>
    </div>
@endforeach

            </div>
        </div>


        <div class="mb-3">
            <label class="form-label">Gambar Baru (maks 10):</label>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
        </div>

        {{-- Bisa tambahkan cropper.js jika ingin cropping sebelum submit --}}

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url('/admin/project/' ) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

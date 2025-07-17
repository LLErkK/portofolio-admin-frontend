@extends('layouts.admin')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Tambah Proyek</h2>
    <form method="POST" action="{{ route('admin.project.store') }}" enctype="multipart/form-data" class="mb-5">
        @csrf
        <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Nama Proyek"></div>
        <div class="mb-3"><textarea name="description" class="form-control" rows="3" placeholder="Deskripsi"></textarea></div>
        <div class="mb-3"><input type="text" name="link" class="form-control" placeholder="Link Proyek"></div>
        <div class="mb-3"><input type="text" name="tech_stack" class="form-control" placeholder="Stack Teknologi"></div>
        <div class="mb-3"><input type="file" name="images[]" multiple class="form-control"></div>
        <button class="btn btn-primary">Simpan</button>
    </form>

    <hr>

    <h3 class="mb-3">Daftar Proyek</h3>
    <div class="row">
        @foreach ($projects as $project)
            @php
                $imageList = [];
                $raw = $project['images'] ?? null;

                if (is_array($raw)) {
                    if (count($raw) === 1 && is_string($raw[0])) {
                        $jsonCandidate = $raw[0];
                        if (str_starts_with($jsonCandidate, '/storage/[')) {
                            $jsonCandidate = substr($jsonCandidate, strlen('/storage/'));
                        }
                        $decoded = json_decode($jsonCandidate, true);
                        if (is_array($decoded)) {
                            $imageList = $decoded;
                        }
                    } else {
                        $imageList = $raw;
                    }
                } elseif (is_string($raw)) {
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

            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $project['name'] }}</h5>
                        <p class="card-text">{{ $project['description'] }}</p>
                        <p><strong>Stack:</strong> {{ $project['tech_stack'] }}</p>
                        <p><a href="{{ $project['link'] }}" target="_blank">{{ $project['link'] }}</a></p>

                        @if (count($imageList))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($imageList as $image)
                                <img src="{{ env('API_BASE_URL') . '/storage/' . $image }}" width="100" height="100" style="object-fit: cover;" class="rounded border">
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.project.edit', $project['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.project.destroy', $project['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
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

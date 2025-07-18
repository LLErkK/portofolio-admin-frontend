@extends('layouts.admin')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="mb-4">Daftar Sertifikat</h2>

    {{-- Form Tambah Sertifikat --}}
    <form action="{{ route('admin.certificate.store') }}" method="POST" enctype="multipart/form-data" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="title">Judul Sertifikat</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="issuer">Penerbit</label>
            <input type="text" name="issuer" id="issuer" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="year">Tahun</label>
            <input type="number" name="year" id="year" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="image">Gambar Sertifikat</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Tambah Sertifikat</button>
    </form>

    {{-- Tabel Sertifikat --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $item)
                <tr>
                    <td>
                        @if (!empty($item['image']))
                            <img src="{{ env('API_BASE_URL') . '/storage/' . $item['image'] }}" alt="Certificate Image" width="100">
                        @else
                            <span>Tidak ada gambar</span>
                        @endif

                    </td>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['issuer'] }}</td>
                    <td>{{ $item['year'] }}</td>
                    <td>
                        <form action="{{ route('admin.certificate.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                        {{-- Tombol edit bisa diarahkan ke halaman baru atau modal --}}
                        <a href="{{ route('admin.certificate.edit', $item['id']) }}" class="btn btn-warning btn-sm mt-1">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

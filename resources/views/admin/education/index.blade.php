@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Daftar Riwayat Pendidikan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.education.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label>Nama Sekolah *</label>
            <input type="text" name="school_name" class="form-control" required value="{{ old('school_name') }}">
        </div>
        <div class="mb-3">
            <label>Tahun Masuk *</label>
            <input type="date" name="start_year" class="form-control" required value="{{ old('start_year') }}">
        </div>
        <div class="mb-3">
            <label>Tahun Lulus</label>
            <input type="date" name="end_year" class="form-control" value="{{ old('end_year') }}">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="address" class="form-control">{{ old('address') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sekolah</th>
                <th>Tahun Masuk</th>
                <th>Tahun Lulus</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($educations as $edu)
                <tr>
                    
                    <td>{{ $edu['school_name'] }}</td>
                    <td>{{ $edu['start_year'] }}</td>
                    <td>{{ $edu['end_year'] ?? '-' }}</td>
                    <td>{{ $edu['address'] ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.education.edit', $edu['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.education.destroy', $edu['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus riwayat ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data pendidikan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

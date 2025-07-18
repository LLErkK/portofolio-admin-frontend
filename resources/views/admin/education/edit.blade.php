@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Edit Riwayat Pendidikan</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <form action="{{ route('admin.education.update', $education['id']) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label>Nama Sekolah *</label>
            <input type="text" name="school_name" class="form-control" required value="{{ old('school_name', $education['school_name']) }}">
        </div>
        <div class="mb-3">
            <label>Tahun Masuk *</label>
            <input type="date" name="start_year" class="form-control" required value="{{ old('start_year', $education['start_year']) }}">
        </div>
        <div class="mb-3">
            <label>Tahun Keluar</label>
            <input type="date" name="end_year" class="form-control" value="{{ old('end_year', $education['end_year']) }}">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="address" class="form-control">{{ old('address', $education['address']) }}</textarea>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.education.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

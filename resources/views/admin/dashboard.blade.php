@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Selamat Datang di Dashboard Admin</h1>
    <div class="alert alert-success">
        Anda login sebagai <strong>{{ session('admin_name') ?? 'Admin' }}</strong>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Proyek</div>
                <div class="card-body">
                    <h5 class="card-title">10</h5>
                    <p class="card-text">Proyek yang telah diposting.</p>
                </div>
            </div>
        </div>
        <!-- Tambah card lainnya sesuai kebutuhan -->
    </div>
</div>
@endsection

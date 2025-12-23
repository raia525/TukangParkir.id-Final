@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 bg-success text-white">
                <h5>Available</h5>
                <p class="fs-3">{{ $available }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-primary text-white">
                <h5>Reserved</h5>
                <p class="fs-3">{{ $reserved }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-danger text-white">
                <h5>Occupied</h5>
                <p class="fs-3">{{ $active }}</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.reservasi') }}" class="btn btn-primary">Lihat Semua Reservasi</a>
        <a href="{{ route('admin.history') }}" class="btn btn-success">Lihat Histori Pembayaran</a>
    </div>
</div>
@endsection

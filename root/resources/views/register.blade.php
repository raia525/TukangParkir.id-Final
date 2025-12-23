@extends('layouts.login_register')

@section('title', 'Register')

@section('content')
<form method="POST" action="/register" class="card-login">
    @csrf
    
    <h2><i class="bi bi-person-plus-fill"></i> Daftar Akun Baru</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="no_telp" maxlength="13" class="form-control" required>
        </div>

        <div class="col-md-6 mb-4">
            <label class="form-label">Nomor Kendaraan</label>
            <input type="text" name="nomor_kendaraan" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="col-md-6 mb-4">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

    </div>

    <button type="submit" class="btn btn-gradient">Daftar</button>

    <a href="/login">Sudah punya akun? Login di sini</a>
</form>
@endsection

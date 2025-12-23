@extends('layouts.login_register')

@section('title', 'Login')

@section('content')
<form method="POST" action="/login" class="card-login">
    @csrf

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <h2><i class="bi bi-box-arrow-in-right"></i> Login</h2>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-gradient">Login</button>

    <a href="/register" class="d-block text-center mt-3">Belum punya akun? Daftar</a>
</form>
@endsection

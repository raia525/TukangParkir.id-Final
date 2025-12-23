@extends('layouts.main')
@section('title', 'Home')
@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Header Section -->
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold mb-1">
                Hello, 
                <span class="text-gradient">{{ Auth::user()->nama }}</span>
            </h1>
            <p class="fs-5 text-dark mb-0">
                Welcome to <span class="fw-semibold">TukangParkir.id</span>!
            </p>
            <p class="text-muted mb-1">Click the button to reserve a parking space.</p>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-end">
            <a href="/reservasi" class="btn btn-gradient btn-lg px-5 py-3 rounded-pill">
                Reserve Parking
            </a>
        </div>
        <br><br>
    </div>

    <!-- Weather Cards Section -->
    <div class="row g-4 mb-4">

        <!-- Temperature Card -->
        <div class="col-12 col-lg-4">
            <div class="weather-card-main rounded-4 p-4 h-100 position-relative overflow-hidden">
                <div class="location-badge">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    <span id="info_lokasi">Jakarta, Indonesia</span>
                </div>

                <div class="weather-value-bottom">
                    <h1 class="display-1 fw-bold text-white mb-0" id="temperature">28.1°C</h1>
                </div>

                <div class="weather-icon-bg">
                    <i class="bi bi-cloud-sun-fill"></i>
                </div>
            </div>
        </div>

        <!-- Wind Card -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="weather-card-secondary rounded-4 p-4 h-100 position-relative overflow-hidden">
                <div class="info-badge mb-3">
                    <i class="bi bi-wind me-2"></i>
                    <span>Wind</span>
                </div>

                <div class="weather-value-bottom">
                    <h1 class="display-3 fw-bold text-white mb-0" id="wind_speed">9.6<span class="fs-4">m/s</span></h1>
                </div>

                <div class="weather-icon-small">
                    <i class="bi bi-wind"></i>
                </div>
            </div>
        </div>

        <!-- Humidity Card -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="weather-card-secondary rounded-4 p-4 h-100 position-relative overflow-hidden">
                <div class="info-badge mb-3">
                    <i class="bi bi-droplet-half me-2"></i>
                    <span>Humidity</span>
                </div>

                <div class="weather-value-bottom">
                    <h1 class="display-3 fw-bold text-white mb-0" id="humidity">71<span class="fs-4">%</span></h1>
                </div>

                <div class="weather-icon-small">
                    <i class="bi bi-droplet-half"></i>
                </div>
            </div>
        </div>
    </div>

        <!-- Date/Time Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="datetime-container">
                <h5 class="text-dark mb-1">Date/Time (WIB):</h5>
                <span id="date" data-now="{{ \App\Helpers\SystemTime::now()->format('Y-m-d\\TH:i:sP') }}" class="fw-bold fs-4"></span>
                </br>
                <span id="time" class="fs-5"></span>
            </div>
        </div>
    </div>

    
</div>
@endsection
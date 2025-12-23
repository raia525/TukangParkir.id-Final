<nav class="navbar navbar-expand-lg navbar-dark sticky-top px-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/logo_tukangparkir.png') }}" alt="MyApp Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin') ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/reservasi') ? 'active fw-bold' : '' }}" href="{{ route('admin.reservasi') }}">Reservasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/history') ? 'active fw-bold' : '' }}" href="{{ route('admin.history') }}">Histori</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active fw-bold' : '' }}" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('reservasi') ? 'active fw-bold' : '' }}" href="/reservasi">Reservasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('reservasi/history') ? 'active fw-bold' : '' }}" href="/reservasi/history">Histori</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="/logout">Logout</a>
                </li>

                <li class="nav-item">
                    <div class="profile-circle bg-primary text-white d-flex justify-content-center align-items-center rounded-circle">
                        @if(Auth::check())
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
@extends('layouts.main')

@section('title', 'Reservasi Parkir')

@section('content')

{{-- SIMULASI WAKTU (DEMO MODE) --}}
<div class="rounded rounded-4 bg-white p-5 mb-4 shadow-sm">
    <h5 class="fw-bold mb-2">⏱️ Simulasi Waktu Sistem</h5>

    <form action="{{ route('system.time.set') }}" method="POST" class="mb-2">
        @csrf
        <div class="input-group">
            <input type="datetime-local" name="manual_time" class="form-control" required>
            <button class="btn btn-warning fw-bold">Set Waktu</button>
        </div>
    </form>

    <form action="{{ route('system.time.reset') }}" method="POST">
        @csrf
        <button class="btn btn-outline-secondary w-100">
            🔄 Reset ke Waktu Server
        </button>
    </form>

    <div class="text-center mt-2">
        @if(\App\Helpers\SystemTime::isDemo())
            <span class="badge bg-warning text-dark">
                MODE DEMO AKTIF
            </span>
        @else
            <span class="badge bg-success">
                REAL TIME SERVER
            </span>
        @endif
    </div>
</div>

<div class="row mb-5">
    <div class="col-12 reservasi">

        <div class="card shadow-sm p-4">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3 p-3">
                <div>
                    <h1 class="fw-bold title-gradient">Parking Space</h1>
                    <h5>Jakarta, Indonesia</h5>
                </div>
                <p class="clock fw-bold fs-1 mb-0" id="time" data-now="{{ \App\Helpers\SystemTime::now()->format('Y-m-d\\TH:i:sP') }}"></p>
            </div>


            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <div class="row">

                {{-- GRID PARKIR --}}
                <div class="col-md-7 mb-4">
                    <div class="parking-grid d-flex justify-content-center">

                        @foreach($slots as $slot)
                        @php
                            $reserved = $reservations
                                ->where('slot_kode', $slot)
                                ->whereIn('status', ['reserved','entered'])
                                ->first();

                            $isMine = $reserved && $reserved->user_id == $user->id;

                            if($isMine) {
                                $slotClass = 'slot-yellow'; // slot milik user
                            } elseif($reserved && $reserved->status == 'reserved') {
                                $slotClass = 'slot-blue';   // slot reserved orang lain
                            } elseif($reserved && $reserved->status == 'entered') {
                                $slotClass = 'slot-red';    // slot sedang occupied
                            } else {
                                $slotClass = 'slot-green';  // slot kosong
                            }
                        @endphp

                        <div class="slot-box position-relative text-white {{ $slotClass }}">
                            @if($reserved)
                                <img src="{{ asset('images/car.png') }}" class="car-icon">
                            @endif
                            <span class="slot-label">{{ $slot }}</span>
                        </div>
                    @endforeach

                    </div>

                    {{-- LEGEND --}}
                    <div class="d-flex mt-4 justify-content-center flex-wrap">
                        <div class="legend-item"><div class="legend-box slot-green"></div> Available</div>
                        <div class="legend-item"><div class="legend-box slot-blue"></div> Reserved</div>
                        <div class="legend-item"><div class="legend-box slot-red"></div> Occupied</div>
                        <div class="legend-item"><div class="legend-box slot-yellow"></div> Your Vehicle</div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="col-md-5">

                    {{-- OVERVIEW --}}
                    <div class="rounded-4 bg-tukangparkir p-4 mb-4">
                        @php
                            $activeSlots = $reservations->whereIn('status',['reserved','entered'])->count();
                        @endphp

                        <p><b>Available:</b> {{ count($slots) - $activeSlots }}</p>
                        <p><b>Occupied:</b> {{ $reservations->where('status','entered')->count() }}</p>
                        <p><b>Your Vehicle:</b> {{ $userReservation ? 1 : 0 }}</p>
                    </div>

                    {{-- USER RESERVATION --}}
                    @if($userReservation)
                        <div class="rounded-4 bg-body-secondary p-4 shadow-sm">
                            <h5 class="fw-bold mb-3">Reservasi Anda</h5>

                            <p><b>Slot:</b> {{ $userReservation->slot_kode }}</p>
                            <p><b>Nomor Kendaraan:</b> {{ $userReservation->nomor_kendaraan }}</p>
                            <p><b>Mulai:</b> {{ $userReservation->start_time }}</p>
                            <p><b>Status:</b> {{ ucfirst($userReservation->status) }}</p>

                            {{-- QR CODE --}}
                            @if($userReservation->status === 'reserved')
                                <hr>
                                <div class="text-center mt-3">
                                    <p class="fw-bold">QR Masuk</p>
                                    <div id="qrcode-masuk" class="d-inline-block"></div>
                                </div>
                            @endif

                            @if($userReservation->status === 'entered')
                                <hr>
                                <div class="text-center mt-3">
                                    <p class="fw-bold">QR Keluar</p>
                                    <div id="qrcode-keluar" class="d-inline-block"></div>
                                </div>
                            @endif

                            {{-- ACTION --}}
                            <div class="text-center mt-3">

                                {{-- JIKA MASIH RESERVED → BOLEH BATAL --}}
                                @if($userReservation->status === 'reserved')
                                    <a href="{{ route('reservasi.cancel') }}"
                                    class="btn btn-gradient w-100"
                                    onclick="return confirm('Yakin ingin membatalkan reservasi?')">
                                        ❌ Batalkan Reservasi
                                    </a>
                                @endif

                                {{-- JIKA SUDAH MASUK → KELUAR --}}
                                @if($userReservation && $userReservation->status === 'entered')
                                    <form method="POST" action="{{ route('reservasi.exit') }}">
                                        @csrf
                                        <button class="btn btn-danger w-100">
                                            🚗 Keluar Parkir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- FORM RESERVASI --}}
                    @if(!$userReservation and !$paymentReservation)
                        <div class="rounded-4 bg-body-secondary p-4 shadow-sm">
                            <h4 class="fw-bold mb-3">Reservasi Parkiran</h4>

                            <form action="{{ route('reservasi.submit') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nomor Kendaraan</label>
                                    <input type="text" class="form-control"
                                        value="{{ $user->nomor_kendaraan }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Slot Parkir</label>
                                    <select name="slot_kode" class="form-control" required>
                                        <option value="">-- Pilih Slot --</option>
                                        @foreach($slots as $slot)
                                            @if(!$reservations
                                            ->where('slot_kode',$slot)
                                            ->whereIn('status',['reserved','entered'])
                                            ->first())
                                                <option value="{{ $slot }}">{{ $slot }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Waktu Mulai</label>
                                    <input type="datetime-local" class="form-control" name="start_time" required>
                                </div>

                                <button type="submit" class="btn btn-gradient w-100">
                                    Reservasi Sekarang
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($paymentReservation)
                    <div class="card mt-4 border-success">
                        <div class="card-body">
                            <h5 class="fw-bold text-success">💰 Pembayaran Parkir</h5>

                            <p><b>Slot:</b> {{ $paymentReservation->slot_kode }}</p>
                            <p><b>Durasi:</b> {{ ceil($paymentReservation->duration_minutes / 60) }} jam</p>
                            <p><b>Total:</b> 
                                Rp {{ number_format($paymentReservation->total_price,0,',','.') }}
                            </p>

                            <form method="POST" action="{{ route('reservasi.pay') }}">
                                @csrf
                                <select name="payment_method" id="payment_method" class="form-select mb-2" required data-plat="{{ $user->nomor_kendaraan }}">
                                    <option value="">Metode Pembayaran</option>
                                    <option value="cash">Tunai</option>
                                    <option value="qris">QRIS</option>
                                    <option value="ewallet">E-Wallet</option>
                                </select>

                                <!-- Tempat QR Code -->
                                <div id="payment_code" class="mb-2" style="display:none;"></div>

                                <button class="btn btn-success w-100">
                                    ✅ Bayar Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
const paymentMethod = document.getElementById('payment_method');
const paymentCodeDiv = document.getElementById('payment_code');
let qr;

paymentMethod.addEventListener('change', function() {
    const method = this.value;
    let platNomor = this.dataset.plat || ''; 

    platNomor = platNomor.replace(/\s+/g, '');

    paymentCodeDiv.innerHTML = '';
    if (qr) qr.clear();

    if (method === 'qris') {
        paymentCodeDiv.style.display = 'block';
        paymentCodeDiv.innerHTML = '<p>Scan QR berikut untuk membayar via QRIS:</p>';
        // Buat QR Code
        qr = new QRCode(paymentCodeDiv, {
            text: `https://example.com/bayar/qris/${platNomor}`, // link pembayaran QRIS
            width: 200,
            height: 200,
        });
    } else if (method === 'ewallet') {
        paymentCodeDiv.style.display = 'block';
        paymentCodeDiv.innerHTML = `
            <p>Gunakan kode berikut untuk E-Wallet (Gopay):</p>
            <code>EWALLET${platNomor}</code>
        `;
    } else if (method === 'cash') {
        paymentCodeDiv.style.display = 'block';
        paymentCodeDiv.innerHTML = `<p>Silakan siapkan uang tunai sesuai total pembayaran dan sebutkan nomor kendaraan anda: ${platNomor}.</p>`;

    }else {
        paymentCodeDiv.style.display = 'none';
    }
});
</script>

@endsection

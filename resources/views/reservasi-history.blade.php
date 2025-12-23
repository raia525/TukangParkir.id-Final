@extends('layouts.main')

@section('title', 'Histori Parkir')

@section('content')
<div class="row">
    <div class="col-12">

        <h2 class="mb-4">📜 Histori Parkir</h2>

        @forelse($history as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <p><b>Slot:</b> {{ $item->slot_kode }}</p>
                    <p><b>Nomor Kendaraan:</b> {{ $item->nomor_kendaraan }}</p>
                    <p><b>Masuk:</b> {{ $item->start_time }}</p>
                    <p><b>Keluar:</b> {{ $item->end_time }}</p>
                    <p><b>Durasi:</b> {{ ceil($item->duration_minutes / 60) }} jam</p>
                    <p><b>Total:</b> Rp {{ number_format($item->total_price,0,',','.') }}</p>
                    <p><b>Bayar:</b> {{ strtoupper($item->payment_method) }}</p>
                </div>
            </div>
        @empty
            <p>Belum ada histori parkir.</p>
        @endforelse

    </div>
</div>
@endsection

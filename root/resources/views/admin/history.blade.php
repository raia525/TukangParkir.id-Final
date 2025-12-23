@extends('layouts.main')

@section('title', 'Histori Pembayaran')

@section('content')
<h2>Histori Pembayaran</h2>
<table class="table table-hover table-striped align-middle">
    <thead class="thead-dark">
        <tr>
            <th>User</th>
            <th>Slot</th>
            <th>Mulai</th>
            <th>Berakhir</th>
            <th class="text-end">Durasi (menit)</th>
            <th class="text-end">Total Harga</th>
            <th>Metode Bayar</th>
            <th>Tanggal Bayar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($history as $h)
        <tr>
            <td>
                <div class="fw-semibold">{{ $h->user->nama ?? 'N/A' }}</div>
                <small class="text-muted">ID: {{ $h->user->id ?? '-' }}</small>
            </td>
            <td>
                <span class="badge bg-light text-dark border">
                    {{ $h->slot_kode }}
                </span>
            </td>
            <td>
                {{ $h->start_time }}
            </td>
            <td>
                {{ $h->end_time }}
            </td>
            <td class="text-end">
                {{ $h->duration_minutes ? number_format($h->duration_minutes, 0, ',', '.') : '-' }}
            </td>
            <td class="text-end">
                @if($h->total_price)
                    Rp {{ number_format($h->total_price, 0, ',', '.') }}
                @else
                    -
                @endif
            </td>
            <td>
                <span class="badge bg-secondary text-uppercase">
                    {{ $h->payment_method ?? '-' }}
                </span>
            </td>
            <td>
                {{ $h->paid_at }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

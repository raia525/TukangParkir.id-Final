@extends('layouts.main')

@section('title', 'Reservasi Parkir')

@section('content')
<h2>Daftar Reservasi</h2>
<table class="table table-hover table-striped align-middle">
    <thead class="thead-dark">
        <tr>
            <th>User</th>
            <th>Slot</th>
            <th>Mulai</th>
            <th>Berakhir</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservations as $r)
        <tr>
            <td>
                <div class="fw-semibold">{{ $r->user->nama ?? 'N/A' }}</div>
                <small class="text-muted">ID: {{ $r->user->id ?? '-' }}</small>
            </td>
            <td>
                <span class="badge bg-light text-dark border">
                    {{ $r->slot_kode }}
                </span>
            </td>
            <td>{{ $r->start_time }}</td>
            <td>{{ $r->end_time }}</td>
            <td class="text-center">
                @php
                    $status = strtolower($r->status);
                    $badgeClass = match ($status) {
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-success',
                        'rejected', 'cancelled' => 'bg-danger',
                        'ongoing' => 'bg-info text-dark',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">
                    {{ ucfirst($status) }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

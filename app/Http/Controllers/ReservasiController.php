<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SystemTime;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $slots = ['A1', 'A2'];
        $user  = Auth::user();
        $now   = SystemTime::now();

        // AUTO: reserved → entered
        Reservasi::where('status', 'reserved')
            ->where('start_time', '<=', $now)
            ->update(['status' => 'entered']);

        // Slot aktif (untuk grid)
        $reservations = Reservasi::whereIn('status', ['reserved','entered'])->get();

        // Reservasi aktif (reserved / entered)
        $userReservation = Reservasi::where('user_id', $user->id)
            ->whereIn('status', ['reserved','entered'])
            ->first();

        // Tagihan (finished tapi belum dibayar)
        $paymentReservation = Reservasi::where('user_id', $user->id)
            ->where('status', 'finished')
            ->where('payment_status', 'unpaid')
            ->first();

        return view('reservasi', compact(
            'slots',
            'reservations',
            'userReservation',
            'paymentReservation',
            'user'
        ));
    }

    /* ===================== RESERVASI ===================== */

    public function submit(Request $request)
    {
        $request->validate([
            'slot_kode'  => 'required',
            'start_time' => 'required|date'
        ]);

        $user = Auth::user();
        $now  = SystemTime::now();

        if ($now->gte($request->start_time)) {
            return back()->with('error','Waktu reservasi harus di masa depan.');
        }

        // Cek slot bentrok
        $exists = Reservasi::where('slot_kode',$request->slot_kode)
            ->whereIn('status',['reserved','entered'])
            ->exists();

        if ($exists) {
            return back()->with('error','Slot sudah digunakan.');
        }

        Reservasi::create([
            'user_id'          => $user->id,
            'slot_kode'        => $request->slot_kode,
            'nomor_kendaraan'  => $user->nomor_kendaraan,
            'start_time'       => $request->start_time,
            'status'           => 'reserved',
            'payment_status'   => 'unpaid'
        ]);

        return back()->with('success','Reservasi berhasil dibuat.');
    }

    public function cancel()
    {
        $user = Auth::user();

        $res = Reservasi::where('user_id',$user->id)
            ->where('status','reserved')
            ->first();

        if (!$res) {
            return back()->with('error','Tidak ada reservasi yang bisa dibatalkan.');
        }

        $res->update(['status'=>null]);

        return back()->with('success','Reservasi dibatalkan.');
    }

    /* ===================== PARKIR ===================== */

    public function exitParking()
    {
        $user = Auth::user();
        $now  = SystemTime::now();

        $res = Reservasi::where('user_id',$user->id)
            ->where('status','entered')
            ->first();

        if (!$res) {
            return back()->with('error','Tidak ada parkir aktif.');
        }

        $minutes = Carbon::parse($res->start_time)->diffInMinutes($now);
        $hours   = max(1, ceil($minutes / 60));
        $rate    = 5000;

        $res->update([
            'end_time'          => $now,
            'duration_minutes' => $minutes,
            'total_price'      => $hours * $rate,
            'status'            => 'finished',
            'payment_status'    => 'unpaid'
        ]);

        return redirect()->route('reservasi')
        ->with('success','Parkir selesai. Silakan bayar.');
    }

    /* ===================== PEMBAYARAN ===================== */

    public function pay(Request $request)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $user = Auth::user();

        $res = Reservasi::where('user_id',$user->id)
            ->where('status','finished')
            ->where('payment_status','unpaid')
            ->first();

        if (!$res) {
            return back()->with('error','Tidak ada tagihan.');
        }

        $res->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at'        => SystemTime::now()
        ]);

        return back()->with('success','Pembayaran berhasil.');
    }

    /* ===================== HISTORI ===================== */
    public function history()
    {
        $user = Auth::user();

        // Ambil semua reservasi yang sudah selesai dan dibayar
        $history = Reservasi::where('user_id', $user->id)
            ->where('status', 'finished')
            ->where('payment_status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->get();

        return view('reservasi-history', compact('history'));
    }

    /* ===================== API (OPSIONAL) ===================== */

    public function syncFirebaseStatus()
    {
        $user = Auth::user();

        $res = Reservasi::where('user_id',$user->id)
            ->latest()
            ->first();

        return response()->json([
            'status' => $res?->status
        ]);
    }
}
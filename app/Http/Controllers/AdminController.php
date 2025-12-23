<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\User;
use App\Helpers\SystemTime;

class AdminController extends Controller
{
    public function index()
    {
        $totalSlots = 2; 
        $reservations = Reservasi::get();
        $active = $reservations->where('status','entered')->count();
        $reserved = $reservations->where('status','reserved')->count();
        $available = $totalSlots - ($active + $reserved);

        return view('admin.dashboard', compact('totalSlots','active','reserved','available'));
    }

    public function reservasi()
    {
        $reservations = Reservasi::with('user')->orderBy('start_time','desc')->get();
        return view('admin.reservasi', compact('reservations'));
    }

    public function history()
    {
        $history = Reservasi::with('user')
            ->where('payment_status','paid')
            ->orderBy('paid_at','desc')
            ->get();

        return view('admin.history', compact('history'));
    }
}
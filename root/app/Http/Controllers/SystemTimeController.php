<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SystemTime;

class SystemTimeController extends Controller
{
    public function set(Request $request)
    {
        $request->validate([
            'manual_time' => 'required'
        ]);

        $time = str_replace('T', ' ', $request->manual_time) . ':00';

        \App\Helpers\SystemTime::setTime($time);

        return back()->with('success', 'Waktu simulasi diubah.');
    }

    public function reset()
    {
        SystemTime::reset();

        return back()->with('success', 'Waktu dikembalikan ke waktu server.');
    }
}
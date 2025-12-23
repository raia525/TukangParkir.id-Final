<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SystemTime
{
    public static function now(): Carbon
    {
        if (Session::has('manual_time')) {
            return Carbon::parse(Session::get('manual_time'))
                ->setTimezone(config('app.timezone'));
        }

        return Carbon::now(config('app.timezone'));
    }

    public static function setTime(string $time): void
    {
        Session::put('manual_time', $time);
    }

    public static function reset(): void
    {
        Session::forget('manual_time');
    }

    public static function isDemo(): bool
    {
        return Session::has('manual_time');
    }
}
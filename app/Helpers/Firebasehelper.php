<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FirebaseHelper
{
    protected static $url;
    protected static $secret;

    public static function init()
    {
        self::$url = rtrim(env('FIREBASE_DB_URL'), '/');
        self::$secret = env('FIREBASE_DB_SECRET');
    }

    public static function get($path)
    {
        self::init();
        $res = Http::get(self::$url . '/' . trim($path, '/') . '.json', [
            'auth' => self::$secret
        ]);

        return $res->ok() ? $res->json() : null;
    }

    public static function set($path, $data)
    {
        self::init();
        $res = Http::put(self::$url . '/' . trim($path, '/') . '.json', $data);
        return $res->ok() ? $res->json() : null;
    }
}
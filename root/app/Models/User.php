<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'no_telp',
        'nomor_kendaraan',
        'role', // jangan lupa kalau pakai role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function reservasi()
    {
        return $this->hasOne(\App\Models\Reservasi::class);
    }
}
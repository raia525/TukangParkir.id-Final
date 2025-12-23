<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $fillable = [
        'user_id',
        'slot_kode',
        'nomor_kendaraan',
        'start_time',
        'end_time',
        'slot_parkir',
        'status',             
        'duration_minutes',   
        'total_price',        
        'payment_status',     
        'payment_method',     
        'paid_at',            
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
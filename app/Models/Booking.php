<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    // Daftarkan kolom agar bisa diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'service_id',
        'customer_name',
        'motor_model',
        'booking_time',
        'estimated_finish_at',
        'actual_start_at',
        'actual_finish_at',
        'status',
    ];

    // Relasi: Setiap booking punya satu layanan
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Relasi: Setiap booking milik satu user (pelanggan/admin)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
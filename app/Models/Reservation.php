<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'seat_number',
        'passenger_name',
        'passenger_type',
        'trip_type',
        'round_trip_group_id',
        'discount_id_number',
        'status',
        'transaction_id',
        'payment_method',
        'cancellation_status',
        'cancellation_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
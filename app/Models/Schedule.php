<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'available',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time'   => 'datetime',
    ];
    
    // Relationship: A Schedule belongs to one Bus
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    // Relationship: A Schedule belongs to one Route
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
    public function reservations()
    {
        // A Schedule has many Reservations
        return $this->hasMany(Reservation::class);
    }
}
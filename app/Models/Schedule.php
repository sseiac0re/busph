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
    
    // Accessor for departure date only
    public function getDepartureDateAttribute()
    {
        return $this->departure_time ? $this->departure_time->format('Y-m-d') : null;
    }
    
    // Accessor for departure time only (HH:mm format)
    public function getDepartureTimeOnlyAttribute()
    {
        return $this->departure_time ? $this->departure_time->format('H:i') : null;
    }
    
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
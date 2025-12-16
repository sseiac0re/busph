<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'bus_id',
        'active_days',
        'start_time',
        'end_time',
        'frequency_minutes',
    ];

    // Cast the JSON column to an Array automatically
    protected $casts = [
        'active_days' => 'array',
        'start_time' => 'datetime:H:i', // Optional: easier formatting
        'end_time' => 'datetime:H:i',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
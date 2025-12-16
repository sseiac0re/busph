<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    // This property tells Laravel which columns are safe to save
    protected $fillable = [
        'bus_number',
        'plate_number',
        'type',
        'capacity',
        'operator',
        'status',
    ];
}
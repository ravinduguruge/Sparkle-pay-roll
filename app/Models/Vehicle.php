<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'name',
        'type',
        'fuel_rate_per_km',
        'bus_ticket_amount',
        'is_active',
    ];
}

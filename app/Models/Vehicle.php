<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bookings;
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'vehicle_name',
    'vehicle_type',
    'seating_capacity',
    'additional_features',
    'registration_number',
    'brand',
    'model',
    'fuel_type',
    'rate_per_hour',
    'rate_max_8hour',
    'rate_per_day',
    'vehicle_image',
    'description',
    'status',
    'insurance_upto'
];
function bookings()
    {
        return $this->hasMany(Bookings::class, 'vehicle_id');
    }
}

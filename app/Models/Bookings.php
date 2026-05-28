<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicles;
use App\Models\customers;


class Bookings extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'Amount',
        'booking_date',
        'return_date',
        'status',
    ];

    public function vehicle()
    {
       return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function customer()
    {
        return $this->belongsTo(customers::class, 'customer_id');
    }
}

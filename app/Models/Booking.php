<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicle;
use App\Models\Customer;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'amount',
        'branch',
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
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function Payment()
    {
    return $this->hasMany(Payment::class, 'booking_id');
    }
}

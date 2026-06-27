<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Booking; 
class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'customer_id',
        'vehicle_id',
        'payment_date',
        'payment_amount',
        'payment_mode',
        'payment_status',
    ];
   public function vehicle()
    {
       return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
     public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}

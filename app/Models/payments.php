<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicles;
use App\Models\customers;
use APP\Models\bookings;
class payments extends Model
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
        return $this->belongsTo(customers::class, 'customer_id');
    }
     public function booking()
    {
        return $this->belongsTo(bookings::class, 'booking_id');
    }
}

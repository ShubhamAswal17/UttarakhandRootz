<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicles;
class Maintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_name',
        'vehicle_id',
        'branch',
        'vehicle_name',
        'registration_number',
        'service_date',
        'insurance_upto',
        'return_date',
        'service_issue',
        'vendor_name',
        'bill_image',
        'payment_type',
        'payment_status',
        'service_amount',
        'service_status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}

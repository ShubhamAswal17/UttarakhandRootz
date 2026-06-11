<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicle;
class customers extends Model
{
    use HasFactory;
     protected $fillable = [

        // Customer Details
        'customer_name',
        'phone_number',
        'email',
        'address',
        'licence_number',
        'bill_number',

        // ID Proof
        'id_proof_type',
        'id_proof_number',

        // Vehicle
        'vehicle_id',
        'vehicle_name',
        'vehicle_type',
        'registration_number',
        'payment_status',

        // Rental
        'rental_type',
        'price',


        // Status
        'status'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

        public function vehicle()
        {
            return $this->belongsTo(Vehicle::class, 'vehicle_id');
        }
}

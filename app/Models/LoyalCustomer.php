<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyalCustomer extends Model
{
    use HasFactory;
    protected $fillable = [
        'licence_number',
        'name',
        'email',
        'phone_number',
        'branch',
        'booking_count'
    ];
}

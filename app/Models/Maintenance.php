<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_name',
        'bike_id',
        'service_date',
        'insurance_upto',
        'service_return_date',
        'service_issue',
        'service_amount',
        'service_status',
    ];
}

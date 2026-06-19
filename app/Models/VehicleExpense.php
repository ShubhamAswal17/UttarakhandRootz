<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_name',
        'employee_branch',
        'vehicle_name',
        'registration_number',
        'expense_type',
        'expense_date',
        'vendor_name',
        'bill_image',
        'expense_description',
        'payment_type',
        'expense_amount',
        'expense_status',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'manager_name',
        'manager_branch',
        'expense_type',
        'vendor_name',
        'vendor_number',
        'bill_image',
        'expense_date',
        'expense_description',
        'payment_type',
        'expense_amount',
        'expense_status',
    ];
}

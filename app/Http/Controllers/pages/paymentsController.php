<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\payments;

class paymentsController extends Controller
{
     public function index()
{
    $query = payments::with(['customer', 'booking']);

    // Admin
    if (auth()->user()->role == 'admin') {

        // All payments
    }

    // Manager
    elseif (auth()->user()->role == 'manager') {

        $query->whereHas('booking', function ($q) {
            $q->where('branch', auth()->user()->branch);
        });
    }

    // Employee
    elseif (auth()->user()->role == 'employee') {

        $query->whereHas('booking', function ($q) {
            $q->where('branch', auth()->user()->branch);
        })
        ->whereDate('payments.payment_date', '>=', now()->subDays(7));
    }

    $payments = $query->latest()->get();

    return view('content.pages.pages-payments', compact('payments'));
}
}
<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\bookings;
use App\Models\customers;
use App\Models\Vehicle;
use App\Models\payments;

class HomePage extends Controller
{
  public function index()
  {
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $currentMonthBookings = bookings::whereBetween('booking_date', [$startOfMonth, $endOfMonth])->get();
    $currentMonthCustomers = customers::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
    $totalVehicles = Vehicle::count();
    $currentMonthRevenue = payments::whereBetween('payment_date',[now()->startOfMonth(), now()->endOfMonth()])->sum('payment_Amount');

    return view('content.pages.pages-home', compact('currentMonthBookings', 'currentMonthCustomers', 'totalVehicles', 'currentMonthRevenue'));
  }
}
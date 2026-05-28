<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookings;
class paymentsController extends Controller
{
      public function index()
  {
    $bookings = bookings::where('status', 'booked')->get();
    return view('content.pages.pages-payments', compact('bookings'));
  }
}

<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookings;
use App\Models\vehicles;
use App\Models\customers;

class bookingsController extends Controller
{
     public function index()
  {
    $bookings = bookings::with(['vehicle', 'customer'])->get();
    return view('content.pages.pages-bookings', compact('bookings'));
  }
  public function update(Request $request){
        print_r($request->all());
  }
}

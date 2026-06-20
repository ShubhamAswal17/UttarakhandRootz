<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookings;
use App\Models\Vehicle;
use App\Models\customers;
use App\Models\payments;
 
class bookingsController extends Controller
{
   public function index()
{
    $query = bookings::with(['vehicle', 'customer']);
 
    // Admin
    if (auth()->user()->role == 'admin') {

        $query->whereIn('status', ['booked', 'completed']);
    }

    // Manager
    elseif (auth()->user()->role == 'manager') {

        $query->where('branch', auth()->user()->branch);
    }

    // Employee
    elseif (auth()->user()->role == 'employee') {

        $query->where('branch', auth()->user()->branch)
              ->whereDate('created_at', '>=', now()->subDays(7));
    }

    $bookings = $query->latest()->get();

    return view('content.pages.pages-bookings', compact('bookings'));
}
  public function edit(Request $request, $bookingId){
     
      $booking = bookings::findOrFail($bookingId);
      $customer = customers::find($booking->customer_id);
      $vehicle = Vehicle::find($booking->vehicle_id);
   
      return response()->json([
          'booking' => $booking,
          'customer' => $customer,
          'vehicle' => $vehicle
      ]);
  }
 public function update(Request $request, $bookingId)
{
    $booking = Bookings::findOrFail($bookingId);

    $status = $request->status;

    // Check vehicle availability before booking
    if ($status == 'booked') {

        $alreadyBooked = Bookings::where('vehicle_id', $booking->vehicle_id)
            ->where('status', 'booked')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'status' => 'error',
                'message' => 'This vehicle is already booked by another customer.'
            ], 422);
        }
    }

    // Update booking details
    $booking->booking_date = str_replace('T', ' ', $request->booking_date);
    $booking->return_date  = str_replace('T', ' ', $request->return_date);
    $booking->status       = $status;
    $booking->save();

    /*
    |--------------------------------------------------------------------------
    | BOOKED
    |--------------------------------------------------------------------------
    */
    if ($status == 'booked') {

        $customer = Customers::find($booking->customer_id);

        if ($customer) {
            $customer->payment_status = 'paid';
            $customer->save();
        }

        $vehicle = Vehicle::find($booking->vehicle_id);

        if ($vehicle) {
            $vehicle->status = 'booked';
            $vehicle->save();
        }

        Payments::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'booking_id'     => $booking->id,
                'vehicle_id'     => $booking->vehicle_id,
                'customer_id'    => $booking->customer_id,
                'payment_date'   => $booking->booking_date,
                'payment_amount' => $booking->amount,
                'payment_mode'   => $request->paymentType,
                'payment_status' => 'Paid',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETED
    |--------------------------------------------------------------------------
    */
    elseif ($status == 'completed') {

        $vehicle = Vehicle::find($booking->vehicle_id);

        if ($vehicle) {
            $vehicle->status = 'Available';
            $vehicle->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CANCELLED
    |--------------------------------------------------------------------------
    */
    elseif ($status == 'cancelled') {

        $vehicle = Vehicle::find($booking->vehicle_id);

        if ($vehicle) {
            $vehicle->status = 'Available';
            $vehicle->save();
        }
    }

    return back()->with('success', 'Booking updated successfully.');
}
}
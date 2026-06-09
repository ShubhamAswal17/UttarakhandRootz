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
    $bookings = bookings::with(['vehicle', 'customer'])->get();
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
  public function update(Request $request, $bookingId){
      $booking = bookings::findOrFail($bookingId);
    $booking->booking_date = str_replace('T', ' ', $request->booking_date);
    $booking->return_date = str_replace('T', ' ', $request->return_date);
    $booking->status = $request->status;
    $booking->save();

    if ($booking->status === 'completed') {
        $vehicle = Vehicle::find($booking->vehicle_id);
        $vehicle->status = 'Available';
        $vehicle->save();
    }
   
    if ($booking->status === 'booked') {

        $vehicle = Vehicle::find($booking->vehicle_id);
        $vehicle->status = 'booked';
        $vehicle->save();

        payments::updateOrCreate(
            ['booking_id' => $booking->id],
                [
                    'booking_id'      => $booking->id,
                    'vehicle_id'      => $booking->vehicle_id,
                    'customer_id'     => $booking->customer_id,
                    'payment_date'    => $booking->booking_date,
                    'payment_amount'  => $booking->Amount,
                    'payment_mode'    => $request->paymentType,
                    'payment_status'  => 'Paid',
                ]
        );
    }



    return back()->with('success', 'Booking updated successfully.');
  }
}

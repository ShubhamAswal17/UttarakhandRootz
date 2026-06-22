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
    $query = bookings::with(['vehicle', 'customer'])
        ->whereHas('customer');

    if (auth()->user()->role == 'manager') {
        $query->where('branch', auth()->user()->branch);
    }

    if (auth()->user()->role == 'employee') {
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
    $vehicle = Vehicle::find($booking->vehicle_id);
    $customer = Customers::find($booking->customer_id);

    $status = strtolower($request->status);

    $newBookingDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request->booking_date)));
    $newReturnDate  = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request->return_date)));

    if ($status === 'booked') {

        $overlappingBooking = Bookings::where('vehicle_id', $booking->vehicle_id)
        ->where('status', 'booked')
        ->where('id', '!=', $booking->id)
        ->where('booking_date', '<=', $newReturnDate)
        ->where('return_date', '>=', $newBookingDate)
        ->exists();

        if ($overlappingBooking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vehicle is already booked during the selected period.'
            ], 422);
        }
    }

    // Update booking
    $booking->booking_date = str_replace('T', ' ', $request->booking_date);
    $booking->return_date  = str_replace('T', ' ', $request->return_date);
    $booking->status       = $status;
    $booking->save();

    switch ($status) {

        case 'booked':

            if ($customer) {
                $customer->payment_status = 'paid';
                $customer->save();
            }

            if ($vehicle) {
                $vehicle->status = 'Booked';
                $vehicle->save();
            }

            Payments::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'vehicle_id'     => $booking->vehicle_id,
                    'customer_id'    => $booking->customer_id,
                    'payment_date'   => $booking->booking_date,
                    'payment_amount' => $booking->amount,
                    'payment_mode'   => $request->paymentType,
                    'payment_status' => 'Paid',
                ]
            );

            break;

        case 'completed':

            if ($vehicle) {
                $vehicle->status = 'Available';
                $vehicle->save();
            }

            break;

        case 'cancelled':

            if ($vehicle) {
                $vehicle->status = 'Available';
                $vehicle->save();
            }

            $payment = Payments::where('booking_id', $booking->id)->first();

            if ($payment) {
                $payment->payment_status = 'Cancelled';
                $payment->save();
            }

            break;
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Booking updated successfully.'
    ], 200);
}
}
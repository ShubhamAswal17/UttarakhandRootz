<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Booking;
class customersController extends Controller
{
public function index()
{
    $query = Customer::with('vehicle');

    // Admin
    if (auth()->user()->role == 'admin') {

        // All customers
        $vehicles = Vehicle::where('status', 'Available')->get();
     
    }

    // Manager
    elseif (auth()->user()->role == 'manager') {

        $query->whereHas('vehicle', function ($q) {
            $q->where('branch', auth()->user()->branch);
        });

        $vehicles = Vehicle::where('branch', auth()->user()->branch)
                          ->where('status', 'Available')
                          ->get();
    }

    // Employee
    elseif (auth()->user()->role == 'employee') {

        $query->whereHas('vehicle', function ($q) {
                $q->where('branch', auth()->user()->branch);
            })
            ->whereDate('created_at', '>=', now()->subDays(7));

        $vehicles = Vehicle::where('branch', auth()->user()->branch)
                          ->where('status', 'Available')
                          ->get();
    }

    $Customer = $query->get();
   
    

    return view('content.pages.pages-customers', compact('Customer', 'vehicles'));
}
  public function store(Request $request)
{
    $validatedData = $request->validate([
        'customerName' => 'required|string|max:255',
        'phoneNumber' => 'required|string|max:20',
        'emailAddress' => 'required|email|max:255',
        'address' => 'required|string',
        'licenceNumber' => 'required|string|max:255',
        'billNumber' => 'required|string|max:255',
        'idProofType' => 'required|string|max:255',
        'idProofNumber' => 'required|string|max:255',
        'vehicleType' => 'required|string|max:255',
        'registration_no' => 'required|string|max:255',
        'vehicleName' => 'required|string|max:255',
        'vehicle_id' => 'required|exists:vehicles,id',
        'rental_type' => 'required|in:hour,12 hours,day',
        'rentalHours' => 'required_if:rental_type,hour|nullable|integer|min:1',
        'rentalDays' => 'required_if:rental_type,day|nullable|integer|min:1',
        'vehiclePrice' => 'required|numeric|min:0',
        'discount'=>'nullable|numeric|min:0',
        'totalPrice'=>'required|numeric|min:0',
    ]);

    // Check vehicle availability first
    $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

    if ($vehicle->status !== 'Available') {
        return response()->json([
            'status' => 'error',
            'message' => 'This vehicle is already booked.'
        ], 422);
    }

    // Save customer
    $customer = new Customer();
    $customer->customer_name = $validatedData['customerName'];
    $customer->phone_number = $validatedData['phoneNumber'];
    $customer->email = $validatedData['emailAddress'];
    $customer->address = $validatedData['address'];
    $customer->id_proof_type = $validatedData['idProofType'];
    $customer->id_proof_number = $validatedData['idProofNumber'];
    $customer->licence_number = $validatedData['licenceNumber'];
    $customer->bill_number = $validatedData['billNumber'];
    $customer->vehicle_id = $validatedData['vehicle_id'];
    $customer->vehicle_name = $validatedData['vehicleName'];
    $customer->vehicle_type = $validatedData['vehicleType'];
    $customer->registration_number = $validatedData['registration_no'];
    $customer->rental_type = $validatedData['rental_type'];
    $customer->rentalHours = $validatedData['rentalHours'] ?? null;
    $customer->rentalDays = $validatedData['rentalDays'] ?? null;
    $customer->price = $validatedData['vehiclePrice'] ?? 0;
    $customer->discount = $validatedData['discount'] ?? 0;
    $customer->totalPrice = $validatedData['totalPrice'] ?? 0;
    $customer->save();

    // Create booking
    $booking = new Booking();
    $booking->customer_id = $customer->id;
    $booking->vehicle_id = $validatedData['vehicle_id'];
    $booking->amount = $validatedData['totalPrice'] ?? 0;
    $booking->branch = $vehicle->branch;
    $booking->booking_date = now();
    $booking->employee_id = auth()->id();
    $booking->save();

    // Update vehicle status
    $vehicle->status = 'Booked';
    $vehicle->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Customer and booking created successfully.'
    ], 200);
}

}
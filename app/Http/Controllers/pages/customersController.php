<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\customers;
use App\Models\Vehicle;
use App\Models\bookings;
class customersController extends Controller
{
public function index()
{
    $query = Customers::with('vehicle');

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

    $customers = $query->get();
   
    

    return view('content.pages.pages-customers', compact('customers', 'vehicles'));
}
  public function store(Request $request)
  {
    // Validate the incoming request data
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
        'vehiclePrice' => 'nullable|numeric|min:0',
    ]);
     $customer=new customers();
      $customer->customer_name=$validatedData['customerName'];
      $customer->phone_number=$validatedData['phoneNumber'];
      $customer->email=$validatedData['emailAddress'];
      $customer->address=$validatedData['address'];
      $customer->id_proof_type=$validatedData['idProofType'];
      $customer->id_proof_number=$validatedData['idProofNumber'];
      $customer->licence_number=$validatedData['licenceNumber'];
      $customer->bill_number=$validatedData['billNumber'];
      $customer->vehicle_id=$validatedData['vehicle_id'];
      $customer->vehicle_name=$validatedData['vehicleName'];
      $customer->vehicle_type=$validatedData['vehicleType'];
      $customer->registration_number=$validatedData['registration_no'];
      $customer->rental_type=$validatedData['rental_type'];
      $customer->rentalHours=$validatedData['rentalHours'];
      $customer->rentalDays=$validatedData['rentalDays'];
      $customer->price=$validatedData['vehiclePrice'];
      $customer->save();
 
   
    
    $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);
    if ($vehicle->status !== 'Available') {
    return response()->json([
        'status' => 'error',
        'message' => 'This vehicle is not available for booking.'
    ], 422);
    }
    $booking = new bookings();
    $booking->customer_id = $customer->id;
    $booking->vehicle_id = $validatedData['vehicle_id'];
    $booking->amount = $validatedData['vehiclePrice'] ?? 0;
    $booking->branch = $vehicle->branch;
    $booking->booking_date = now();
    $booking->employee_id = auth()->id();
      $booking->save();
       if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Customer data  added successfully'
            ]);
        }
        return redirect()->route('pages-customers')->with('success', 'Customer data added successfully');
  }
}
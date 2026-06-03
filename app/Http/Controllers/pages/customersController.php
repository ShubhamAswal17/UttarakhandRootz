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
    $customers = customers::with('vehicle')->get();
    $vehicles = Vehicle::all();
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
        'rental_type' => 'required|in:hour,8hour,day',
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
      $customer->price=$validatedData['vehiclePrice'];
      $customer->save();


      $booking = new bookings();
      $booking->customer_id = $customer->id;
      $booking->vehicle_id = $validatedData['vehicle_id'];
      $booking->Amount = $validatedData['vehiclePrice'];
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
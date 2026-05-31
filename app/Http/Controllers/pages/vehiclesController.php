<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Bookings;
use Illuminate\Support\Facades\Auth;

class vehiclesController extends Controller
{
      public function index()
  {
    
    $vehicles=Vehicle::all();
    $bookings = Bookings::where('status', 'booked')->get();
    foreach ($vehicles as $vehicle) {
        $vehicle->activeBooking = $bookings->firstWhere('vehicle_id', $vehicle->id);
    }
    return view('content.pages.pages-vehicles', compact('vehicles'));
  }
  public function store(Request $request)
  {
    // Validate the incoming request data
    $validatedData = $request->validate([
        'vehicleName' => 'required|string|max:255',
        'vehicleType' => 'required|string|max:255',
        'seatingCapacity' => 'required|integer|min:1',
        'additionalFeature' => 'nullable|string|max:500',
        'registrationNumber' => 'required|string|max:255|unique:vehicles,registration_number',
        'brand' => 'required|string|max:255',
        'modelName' => 'required|string|max:255',
        'fuelType' => 'required|string|max:255',
        'rentalRatePerHour' => 'required|numeric|min:0',
        'rentalRate8Hours' => 'required|numeric|min:0',
        'rentalRatePerDay' => 'required|numeric|min:0',
        'vehicleImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string|max:1000',
        'insurenceUpto' => 'required|date',
       
    ]);

    $vehicle = new Vehicle();
    $vehicle->user_id = Auth::id();
    $vehicle->vehicle_name = $validatedData['vehicleName'];
    $vehicle->vehicle_type = $validatedData['vehicleType'];
    $vehicle->seating_capacity = $validatedData['seatingCapacity'];
    $vehicle->additional_features = $validatedData['additionalFeature'] ?? null;
    $vehicle->registration_number = $validatedData['registrationNumber'];
    $vehicle->brand = $validatedData['brand'];
    $vehicle->model = $validatedData['modelName'];
    $vehicle->fuel_type = $validatedData['fuelType'];
    $vehicle->rate_per_hour = $validatedData['rentalRatePerHour'];
    $vehicle->rate_max_8hour = $validatedData['rentalRate8Hours'];
    $vehicle->rate_per_day = $validatedData['rentalRatePerDay'];
      if ($request->hasFile('vehicleImage')) {
          $image = $request->file('vehicleImage');
          $imageName = time() . '_' . $image->getClientOriginalName();
          $image->move(public_path('images/vehicles'), $imageName);
          $vehicle->vehicle_image = 'images/vehicles/' . $imageName;
      }
    $vehicle->description = $validatedData['description'] ?? null;
    $vehicle->insurence_upto = $validatedData['insurenceUpto'];
    $vehicle->save();
    if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vehicle added successfully'
            ]);
        }

        
  }
  public function edit($id)
{
    $vehicle = Vehicle::findOrFail($id);

    return response()->json([
        'status' => 'success',
        'vehicle' => $vehicle
    ]);
}
}

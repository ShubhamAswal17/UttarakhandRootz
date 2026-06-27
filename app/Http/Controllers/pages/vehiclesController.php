<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;
 
class vehiclesController extends Controller
{
  public function index()
{
    $query = Vehicle::query();

    // If not admin, show only vehicles from user's branch
    if (auth()->user()->role !== 'admin') {
        $query->where('branch', auth()->user()->branch);
    }

    $vehicles = $query->get();

    $Booking = Booking::where('status', 'booked')
        ->whereIn('vehicle_id', $vehicles->pluck('id'))
        ->get();

    foreach ($vehicles as $vehicle) {
        $vehicle->activeBooking = $Booking->firstWhere('vehicle_id', $vehicle->id);
    }
    $vehiclebrand = Vehicle::distinct()->pluck('brand');
    $vehiclemodel = Vehicle::distinct()->pluck('model');
    return view('content.pages.pages-vehicles', compact('vehicles','vehiclebrand','vehiclemodel'));
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
        'rentalRate12Hours' => 'required|numeric|min:0',
        'rentalRatePerDay' => 'required|numeric|min:0',
        'vehicleImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string|max:1000',
        'insurenceUpto' => 'required|date|after_or_equal:today',
        'vehicleBranch' => 'required|string|max:255',
       
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
    $vehicle->rate_max_12hour = $validatedData['rentalRate12Hours'];
    $vehicle->rate_per_day = $validatedData['rentalRatePerDay'];
      if ($request->hasFile('vehicleImage')) {
          $image = $request->file('vehicleImage');
          $imageName = time() . '_' . $image->getClientOriginalName();
          $image->move(public_path('images/vehicles'), $imageName);
          $vehicle->vehicle_image = 'images/vehicles/' . $imageName;
      }
    $vehicle->description = $validatedData['description'] ?? null;
    $vehicle->insurance_upto = $validatedData['insurenceUpto'];
    $vehicle->branch = $validatedData['vehicleBranch'];
    $vehicle->save();
    return response()->json([
                'status' => 'success',
                'message' => 'Vehicle added successfully'
    ],200);

        
  }
  public function edit($id)
{
    $vehicle = Vehicle::findOrFail($id);
   
    return response()->json([
        'status' => 'success',
        
        'vehicle' => $vehicle
    ]);
}

    public function update(Request $request){
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
         $validatedData = $request->validate([
        'vehicle_id' => 'required|integer',
        'vehicleName' => 'required|string|max:255',
        'vehicleType' => 'required|string|max:255',
        'seatingCapacity' => 'required|integer|min:1',
        'update_additionalFeature' => 'nullable|string|max:500',
        'registrationNumber' => 'required|string|max:255|unique:vehicles,registration_number,' . $vehicle->id,
        'update_brand' => 'required|string|max:255',
        'modelName' => 'required|string|max:255',
        'fuelType' => 'required|string|max:255',
        'rentalRatePerHour' => 'required|numeric|min:0',
        'update_RentalRate12Hours' => 'required|numeric|min:0',
        'rentalRatePerDay' => 'required|numeric|min:0',
        'insurenceUpto' => 'required|date|after_or_equal:today',
        'description' => 'nullable|string|max:1000',
        'vehicleImage' => 'required|string|max:255',
        'status' => 'sometimes|string|in:Available,Maintenance,Delete',
        
        
       
    ]);

    $vehicle->user_id = Auth::id();
    $vehicle->vehicle_name = $validatedData['vehicleName'];
    $vehicle->vehicle_type = $validatedData['vehicleType'];
    $vehicle->seating_capacity = $validatedData['seatingCapacity'];
    $vehicle->additional_features = $validatedData['update_additionalFeature'] ?? null;
    $vehicle->registration_number = $validatedData['registrationNumber'];
    $vehicle->brand = $validatedData['update_brand'];
    $vehicle->model = $validatedData['modelName'];
    $vehicle->fuel_type = $validatedData['fuelType'];
    $vehicle->rate_per_hour = $validatedData['rentalRatePerHour'];
    $vehicle->rate_max_12hour = $validatedData['update_RentalRate12Hours'];
    $vehicle->rate_per_day = $validatedData['rentalRatePerDay'];
    $vehicle->vehicle_image = $validatedData['vehicleImage'];
    $vehicle->description = $validatedData['description'] ?? null;
    $vehicle->insurance_upto = $validatedData['insurenceUpto'];
    $oldStatus = $vehicle->getOriginal('status');
      if (isset($validatedData['status'])) {
        $vehicle->status = $validatedData['status'];
    }
    $vehicle->save();

    if ($oldStatus !== 'Maintenance' && $vehicle->status === 'Maintenance') {

        Maintenance::create([
            'user_name' => Auth::user()->name,
            'vehicle_id' => $vehicle->id,
            'branch' => Auth::user()->branch,
            'vehicle_name' => $vehicle->vehicle_name,
            'registration_number' => $vehicle->registration_number,
            'service_date' => now(),
            'insurance_upto' => $vehicle->insurance_upto,
        ]);
    }
    if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vehicle updated  successfully'
            ]);
        }

    }

    public function availableindex()
{
    $user = auth()->user();

    $vehicles = Vehicle::where('status', 'available')
        ->when(
            in_array($user->role, ['manager', 'employee']),
            fn($query) => $query->where('branch', $user->branch)
        )
        ->get();

    return view('content.pages.pages-vehiclesavailable', compact('vehicles'));
}
public function bookedindex(){
     $user = auth()->user();
    $vehicles = Vehicle::where('status', 'booked')
        ->when(
            in_array($user->role, ['manager', 'employee']),
            fn($query) => $query->where('branch', $user->branch)
        )
        ->get();

    return view('content.pages.pages-vehiclesbooked', compact('vehicles'));
}
}
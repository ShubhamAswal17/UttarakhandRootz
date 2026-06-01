<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Vehicle;

class maintenanceController extends Controller
{
        public function index()
  {
    $maintenance = Maintenance::all();
    return view('content.pages.pages-maintenance', compact('maintenance'));
  }
   public function edit($id)
{
    $maintenance = Maintenance::findOrFail($id);

    return response()->json([
        'status' => 'success',
        'maintenance' => $maintenance
    ]);
}
 public function update(Request $request , $id){
        $maintenance = Maintenance::findOrFail($id);
         $maintenanceData = $request->validate([
        'user_name' => 'required|string|max:255',
        'update_VehicleName' => 'required|string|max:255',
        'service_Date' => 'required|date',
        'service_Return' => 'nullable|date',
        'service_Issue' => 'nullable|string|max:1000',
        'service_Status' => 'required|string|in:Pending,In Progress,Completed',
        'service_Amount' => 'nullable|numeric|min:0',
        
        
       
    ]);

    $maintenance->user_name = $maintenanceData['user_name'];
    $maintenance->vehicle_name = $maintenanceData['update_VehicleName'];
    $maintenance->service_date = $maintenanceData['service_Date'];
    $maintenance->return_date= $maintenanceData['service_Return'];
    $maintenance->service_issue = $maintenanceData['service_Issue'];
    $maintenance->service_status = $maintenanceData['service_Status'];
    $maintenance->service_amount = $maintenanceData['service_Amount'];
      if (isset($maintenanceData['service_Status'])) {
        $maintenance->service_status = $maintenanceData['service_Status'];
    }
    

   if ($maintenance->service_status === 'Completed') {

    $vehicle = Vehicle::where(
        'registration_number',
        $maintenance->registration_number
    )->firstOrFail();

    $vehicle->status = 'Available';
    $vehicle->save();
}

    $maintenance->save();

    if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'maintenance updated  successfully'
            ]);
        }

    }

}
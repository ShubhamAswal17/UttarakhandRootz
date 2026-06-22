<?php

namespace App\Http\Controllers\pages;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Vehicle;

class maintenanceController extends Controller
{
      public function index()
{
    $user = Auth::user();

    if ($user->role == 'admin') {

        // Admin can see all records
        $maintenance = Maintenance::all();

    } elseif ($user->role == 'manager') {

        // Manager can see all records of their branch
        $maintenance = Maintenance::where('branch', $user->branch)->get();

    } elseif ($user->role == 'employee') {

        // Employee can see all records of their branch
        $maintenance = Maintenance::where('branch', $user->branch)->get();

    } else {

        // No access
        $maintenance = collect();
    }

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
 public function update(Request $request, $id)
{
    $maintenance = Maintenance::findOrFail($id);

    $maintenanceData = $request->validate([
        'user_name' => 'required|string|max:255',
        'update_VehicleName' => 'required|string|max:255',
        'service_Date' => 'required|date',
        'service_Return' => 'nullable|date',
        'service_Issue' => 'nullable|string|max:1000',
        'vendor_name' => 'nullable|string|max:255',
        'bill_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:4096',
        'payment_type' => 'nullable|string|max:255',
        'payment_status' => 'nullable|string|max:255',
        'service_Status' => 'required|string|in:Pending,In Progress,Completed',
        'service_Amount' => 'nullable|numeric|min:0',
    ]);


    $maintenance->user_name = $maintenanceData['user_name'];
    $maintenance->vehicle_name = $maintenanceData['update_VehicleName'];
    $maintenance->service_date = $maintenanceData['service_Date'];
    $maintenance->return_date = $maintenanceData['service_Return'];
    $maintenance->service_issue = $maintenanceData['service_Issue'];
    $maintenance->vendor_name = $maintenanceData['vendor_name'];
    $maintenance->payment_type = $maintenanceData['payment_type'];
    $maintenance->payment_status = $maintenanceData['payment_status'];
    $maintenance->service_status = $maintenanceData['service_Status'];
    $maintenance->service_amount = $maintenanceData['service_Amount'];

    // File upload
    if ($request->hasFile('bill_image')) {
        $file = $request->file('bill_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/maintenance'), $filename);
        $maintenance->bill_image = 'uploads/maintenance/' . $filename;
    }

    // Vehicle update on completion
    if ($maintenanceData['service_Status'] === 'Completed') {

        $vehicle = Vehicle::where('registration_number', $maintenance->registration_number)->first();

        if ($vehicle) {
            $vehicle->status = 'Available';
            $vehicle->save();
        }
    }

    $maintenance->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Maintenance updated successfully'
    ]);
}
}
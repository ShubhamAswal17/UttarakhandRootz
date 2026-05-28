<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\vehicle;
class maintenanceController extends Controller
{
        public function index()
  {
    $vehicles = Vehicle::where('status', 'Maintenance')->get();
    return view('content.pages.pages-maintenance', compact('vehicles'));
  }
}

<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoyalCustomer;

class LoyalCustomerController extends Controller
{
 public function index(){
    $loyalcustomer=LoyalCustomer::all();
    return view('content.pages.pages-loyalCustomer',compact('loyalcustomer'));
 }
}


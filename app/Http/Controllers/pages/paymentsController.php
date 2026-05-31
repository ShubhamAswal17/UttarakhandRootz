<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\payments;

class paymentsController extends Controller
{
     public function index()
{
    $payments = payments::with('customer')->get();

    return view('content.pages.pages-payments', compact('payments'));
}
}
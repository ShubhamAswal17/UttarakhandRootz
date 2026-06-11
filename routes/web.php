<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\PreventBackHistory;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

use App\Http\Controllers\pages\bookingsController;
use App\Http\Controllers\pages\CustomersController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\maintenanceController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\paymentsController;
use App\Http\Controllers\pages\vehiclesController;


// authentication


Route::get('/',[LoginBasic::class, 'index'])->name('auth-login');
Route::post('/login',[LoginBasic::class, 'login'])->name('login');
Route::post('/logout',[LoginBasic::class, 'logout'])->name('logout');

Route::get('/register',[RegisterBasic::class, 'index'])->name('auth-register');
Route::post('/register',[RegisterBasic::class, 'store'])->name('auth-register');
// Main Page Route


Route::middleware(['auth', 'prevent-back-history'])->group(function () {


Route::get('/home', [HomePage::class, 'index'])->name('pages-home');

Route::get('/employee', [RegisterBasic::class, 'show'])->name('pages-employee');
Route::get('/employee/update/{employeeId}', [RegisterBasic::class, 'getemployedata'])->name('pages-employee');
Route::post('/employee/update/{employeeid}', [RegisterBasic::class, 'updateemployedata'])->name('employee-update');

Route::get('/employee/approval', [RegisterBasic::class, 'approvalemployee'])->name('employee-approval');
Route::get('/employee/approval/{employeeId}', [RegisterBasic::class, 'approval'])->name('employee-approve');


Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');


Route::get('/customers', [CustomersController::class, 'index'])->name('pages-customers');
Route::post('/customers/add',[CustomersController::class, 'store'])->name('customers-add');


Route::get('/bookings', [bookingsController::class, 'index'])->name('pages-bookings');
Route::get('/bookings/update/{bookingId}', [bookingsController::class, 'edit'])->name('bookings-edit');
Route::post('/booking/{bookingId}/update', [bookingsController::class, 'update'])->name('booking.update');


Route::get('/vehicles', [vehiclesController::class, 'index'])->name('pages-vehicles');
Route::post('/vehicles/add',[vehiclesController::class, 'store'])->name('vehicles-add');
Route::get('/vehicles/edit/{vehicleId}', [vehiclesController::class, 'edit'])->name('vehicles-edit');
Route::post('/vehicles/update', [vehiclesController::class, 'update'])->name('vehicles-update');

Route::get('/vehicles/available', [vehiclesController::class, 'availableindex'])->name('vehicles-available');
Route::get('/vehicles/booked', [vehiclesController::class, 'bookedindex'])->name('booked-available');

Route::get('/payments', [paymentsController::class, 'index'])->name('pages-payments');


Route::get('/maintenance', [maintenanceController::class, 'index'])->name('pages-maintenance');
Route::get('/maintenance/edit/{maintenanceid}', [maintenanceController::class, 'edit'])->name('maintenance-edit');
Route::post('/maintenance/update/{maintenanceid}', [maintenanceController::class, 'update'])->name('maintenance.update');


Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

});








<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
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

$controller_path = 'App\Http\Controllers';

// authentication
Route::get('/',[LoginBasic::class, 'index'])->name('auth-login');
Route::post('/login',[LoginBasic::class, 'login'])->name('login');

Route::get('/register',[RegisterBasic::class, 'index'])->name('auth-register');
Route::post('/register',[RegisterBasic::class, 'store'])->name('auth-register');
// Main Page Route

Route::get('/home', $controller_path . '\pages\HomePage@index')->name('pages-home');
Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');
Route::get('/customers', $controller_path . '\pages\CustomersController@index')->name('pages-customers');
Route::get('/bookings', $controller_path . '\pages\bookingsController@index')->name('pages-bookings');
Route::get('/vehicles', $controller_path . '\pages\vehiclesController@index')->name('pages-vehicles');
Route::get('/payments', $controller_path . '\pages\paymentsController@index')->name('pages-payments');
Route::get('/maintenance', $controller_path . '\pages\maintenanceController@index')->name('pages-maintenance');
// pages
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');




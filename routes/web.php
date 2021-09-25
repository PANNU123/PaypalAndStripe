<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

///payment route
Route::post('/payment/pay', [App\Http\Controllers\PaymentController::class, 'pay'])->name('pay-now');
Route::get('/payment/approve', [App\Http\Controllers\PaymentController::class, 'Paymentapprove'])->name('approval');
Route::get('/payment/cancelled', [App\Http\Controllers\PaymentController::class, 'Paymentcancelled'])->name('cancelled');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('payment',PaymentController::class)->name('payment');
Route::get('callback-payment',[PaymentController::class , 'callback'])->name('notchpay-callback');

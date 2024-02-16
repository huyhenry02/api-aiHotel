<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Payment\PaymentApiController;

Route::get('/payment/get-payment-secret-key', [PaymentApiController::class, 'getClientSecretKey']);
Route::post('/payment/verify-payment', [PaymentApiController::class, 'verifyPayment']);

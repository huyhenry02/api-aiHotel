<?php

use App\Http\Controllers\Api\Invoice\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group([
    'prefix' => 'invoice',
    'middleware' => 'auth:api'
], function () {
    Route::get('list-invoices{per_page?}{page?}', [InvoiceController::class, 'getListInvoices']);
    Route::get('detail{invoice_id?}', [InvoiceController::class, 'getOneInvoice']);
    Route::put('update', [InvoiceController::class, 'updateInvoice']);
    Route::get('payment/get-payment-secret-key{invoice_id?}', [InvoiceController::class, 'getClientSecretKey']);
    Route::post('payment/verify-payment', [InvoiceController::class, 'verifyPaymentInvoice']);
});



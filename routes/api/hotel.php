<?php

use App\Http\Controllers\Api\Hotel\HotelController;
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
Route::get('hotel/list-hotels{per_page?}{page?}', [HotelController::class, 'getListHotels']);
Route::group([
    'prefix' => 'hotel',
    'middleware' => 'auth:api'
], function () {
    Route::post('create-hotel', [HotelController::class, 'createHotel']);
    Route::get('detail{hotel_id?}', [HotelController::class, 'getOneHotel']);
    Route::put('update-hotel', [HotelController::class, 'updateHotel']);
    Route::delete('delete-hotel', [HotelController::class, 'deleteHotel']);
});



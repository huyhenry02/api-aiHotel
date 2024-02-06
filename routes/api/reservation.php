<?php

use App\Http\Controllers\Api\Hotel\HotelController;
use App\Http\Controllers\Api\Reservation\ReservationController;
use App\Http\Controllers\Api\Room\RoomController;
use App\Http\Controllers\Api\Room\RoomTypeController;
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
    'prefix' => 'reservation',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', [ReservationController::class, 'createReservation']);
    Route::put('update', [ReservationController::class, 'updateReservation']);
    Route::put('check-in', [ReservationController::class, 'checkIn']);
    Route::put('check-out', [ReservationController::class, 'checkOut']);
});



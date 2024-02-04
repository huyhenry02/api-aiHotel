<?php

use App\Http\Controllers\Api\Hotel\HotelController;
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
    'prefix' => 'room',
    'middleware' => 'auth:api'
], function () {
    Route::group([
        'prefix' => 'room-type'], function () {
        Route::post('create', [RoomTypeController::class, 'createRoomType']);
        Route::post('update', [RoomTypeController::class, 'updateRoomType']);
        Route::get('get-list', [RoomTypeController::class, 'getRoomTypes']);
        Route::get('get{room_type_id?}', [RoomTypeController::class, 'getOneRoomType']);
        Route::delete('delete{room_type_id?}', [RoomTypeController::class, 'deleteRoomType']);
    });
    Route::group([
        'prefix' => 'room'], function () {
    });
});



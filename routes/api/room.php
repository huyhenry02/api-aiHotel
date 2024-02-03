<?php

use App\Http\Controllers\Api\Hotel\HotelController;
use App\Http\Controllers\Api\Room\RoomController;
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
    'prefix' => 'room-type',
    'middleware' => 'auth:api'], function () {
       Route::post('create', [RoomController::class, 'createRoomType']);
       Route::get('get-list', [RoomController::class, 'getRoomTypes']);
       Route::delete('get-one{room_type_id?}', [RoomController::class, 'getOneRoomType']);
    });
});



<?php

use App\Http\Controllers\Api\Room\RoomController;
use App\Http\Controllers\Api\RoomType\RoomTypeController;
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
    'prefix' => 'room-type', 'middleware' => 'auth:api'], function () {
    Route::post('create', [RoomTypeController::class, 'createRoomType']);
    Route::post('update', [RoomTypeController::class, 'updateRoomType']);
    Route::get('get-list{hotel_id?}', [RoomTypeController::class, 'getRoomTypes']);
    Route::get('get{room_type_id?}', [RoomTypeController::class, 'getOneRoomType']);
    Route::delete('delete{room_type_id?}', [RoomTypeController::class, 'deleteRoomType']);
});
Route::group([
    'prefix' => 'room', 'middleware' => 'auth:api'], function () {
    Route::post('create', [RoomController::class, 'createRoom']);
    Route::post('update', [RoomController::class, 'updateRoom']);
    Route::get('get-list{room_type_id?}{hotel_id?}{floor?}', [RoomController::class, 'getListRoom']);
    Route::delete('delete', [RoomController::class, 'deleteRoom']);
});



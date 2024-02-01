<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Broadcasting\RoomChannel;
use Pusher\Pusher;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::get('test', [UserController::class, 'test']);

Route::get('/test-ws', function (Request $request) {
    RoomChannel::dispatch($request->room_id);
//    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
//        'host' => env('PUSHER_HOST'),
//        'port' => env('PUSHER_PORT'),
//        'scheme' => env('PUSHER_SCHEME'),
//        'encrypted' => false,
//        'useTLS' => false,
//    ]);
//    $pusher->trigger('test-channel', 'test.event', ['name' => 'David Nguyen', 'room_id' => $request->room_id]);

    return 'message sent!';
});

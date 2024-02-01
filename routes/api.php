<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Broadcasting\RoomChannel;
use Pusher\Pusher;

require ('api/account.php');
require ('api/example.php');
require ('api/hotel.php');

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

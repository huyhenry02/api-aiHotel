<?php

use App\Broadcasting\RoomChannel;
use App\Http\Controllers\Api\Example\ExampleController;
use App\Jobs\SendEmailJob;
use App\Mail\CommonMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('example', [ExampleController::class, 'example']);

Route::middleware('auth:api')->group(function () {
    Route::get('example-with-passport', [ExampleController::class, 'exampleWithPassport']);
});


Route::get('email-test', function () {
    $emails = ['huyt@gmail.com', 'test@gmail.com'];
    $cc = ['admin@gmail.com'];
    $bcc = ['user@gmail.com'];
    $data['email'] = $emails;
    $mailable = new CommonMail(data: $data, subject: 'Test mail', view: 'mails.common');
    dispatch(job: new SendEmailJob(mailable: $mailable, email: $emails, cc: $cc, bcc: $bcc));
});
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

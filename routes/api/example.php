<?php

use App\Http\Controllers\Api\Example\ExampleController;
use App\Jobs\SendEmailJob;
use App\Mail\CommonMail;
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

<?php

use App\Http\Controllers\Api\User\UserController;
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
    'prefix' => 'auth'
], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('signup', [UserController::class, 'signup']);

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('user', [UserController::class, 'getUserInfo']);
    });
});


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
    'prefix' => 'auth',
], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('create-user', [UserController::class, 'createUser']);
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('logout', [UserController::class, 'logout']);
    });
});


<?php

use App\Http\Controllers\Api\User\AuthController;
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
    Route::post('login', [AuthController::class, 'login']);
    Route::post('sign-up-for-customer', [UserController::class, 'signUpForCustomer']);
    Route::put('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('send-reset-password-email', [AuthController::class, 'sendResetPasswordEmail'])->name('send-reset-password-email');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::put('change-password', [AuthController::class, 'changePassUser'])->name('change-password');
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('create-user', [UserController::class, 'createUser']);
        Route::get('my-information', [UserController::class, 'getMyInfo']);
        Route::get('user-information{user_id?}', [UserController::class, 'getUserInfo']);
        Route::get('list-user{per_page?}{page?}{type?}', [UserController::class, 'getListUser']);
        Route::delete('delete-user', [UserController::class, 'deleteUser']);
        Route::put('update-user', [UserController::class, 'updateUser']);
    });
});


<?php

use App\Http\Controllers\Api\Hotel\HotelController;
use App\Http\Controllers\Api\Review\ReviewController;
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
    'prefix' => 'review',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', [ReviewController::class, 'createReview']);
    Route::get('list', [ReviewController::class, 'getListReview']);
    Route::get('detail{review_id?}', [ReviewController::class, 'getOneReview']);
    Route::get('filter', [ReviewController::class, 'filterReviews']);
    Route::delete('delete', [ReviewController::class, 'deleteReview']);
});



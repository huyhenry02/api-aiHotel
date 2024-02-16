<?php
use App\Http\Controllers\Api\Service\ServiceController;
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
    'prefix' => 'service', 'middleware' => 'auth:api'], function () {
    Route::post('create', [ServiceController::class, 'createService']);
    Route::post('update', [ServiceController::class, 'updateService']);
    Route::get('get-list', [ServiceController::class, 'getServices']);
    Route::get('get{room_type_id?}', [ServiceController::class, 'getOneService']);
    Route::delete('delete{room_type_id?}', [ServiceController::class, 'deleteService']);
});


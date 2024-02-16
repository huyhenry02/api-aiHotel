<?php

use App\Http\Controllers\Api\Statistic\StatisticController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'statistic',
    'middleware' => 'auth:api'
], function () {
    Route::get('customer', [StatisticController::class, 'statisticCustomer']);
    Route::get('reservation', [StatisticController::class, 'statisticReservation']);
});

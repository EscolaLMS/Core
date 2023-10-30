<?php

use EscolaLms\Core\Http\Controllers\CoreController;
use EscolaLms\Core\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/core'], function () {
    Route::get('/packages', [CoreController::class, 'packages']);
});

Route::prefix('api/core')->group(function () {
    Route::get('health-check', [HealthCheckController::class, 'healthCheck']);
});

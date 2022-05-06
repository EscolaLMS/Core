<?php

use EscolaLms\Core\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/core'], function () {
    Route::get('/packages', [CoreController::class, 'packages']);
});

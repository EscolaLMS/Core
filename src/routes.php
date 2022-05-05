<?php

use EscolaLms\Core\Http\Controllers\CoreController;
use EscolaLms\Core\Http\Facades\Route;

Route::group(['middleware' => Route::apply(['auth:api']), 'prefix' => 'api/core'], function () {
    Route::get('/packages', [CoreController::class, 'packages']);
});

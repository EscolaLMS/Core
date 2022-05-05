<?php

use EscolaLms\Core\Http\Controllers\CoreController;
use EscolaLms\Core\Http\Facades\Route;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/core'], function () {
    Route::get('/packages', [CoreController::class, 'packages']);
});

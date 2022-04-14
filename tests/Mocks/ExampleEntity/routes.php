<?php

use EscolaLms\Core\Tests\Mocks\ExampleEntity\ExampleEntityController;

Route::group(['prefix' => 'api/core'], function () {
    Route::get('/', [ExampleEntityController::class, 'index']);
    Route::get('/period', [ExampleEntityController::class, 'period']);
});

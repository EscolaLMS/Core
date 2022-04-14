<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use Illuminate\Support\ServiceProvider;

class ExampleEntityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}

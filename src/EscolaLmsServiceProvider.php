<?php

namespace EscolaLms\Core;

use EscolaLms\Core\Http\Middleware\SetTimezoneForUserMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class EscolaLmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->getProviders(\EscolaLms\ModelFields\ModelFieldsServiceProvider::class)
            && class_exists(\EscolaLms\ModelFields\ModelFieldsServiceProvider::class)) {
            $this->app->register(\EscolaLms\ModelFields\ModelFieldsServiceProvider::class);
        }
    }

    public function boot()
    {
        $this->app->make(Kernel::class)
            ->pushMiddleware(SetTimezoneForUserMiddleware::class);

        $this->loadConfig();
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrations();
    }

    private function loadConfig(): void
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('escolalms/core.php')
        ], 'escolalms');

        $this->mergeConfigFrom(
            __DIR__ . '/config.php',
            'escolalms.core'
        );

        $config = $this->app->make('config');
        $config->set('auth.guards', array_merge(
            [
                'api' => [
                    'driver' => 'passport',
                    'provider' => 'users',
                ],
            ],
            $config->get('auth.guards', [])
        ));
    }

    private function loadMigrations(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], 'escolalms');

        if (!config('escolalms.core.ignore_migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}

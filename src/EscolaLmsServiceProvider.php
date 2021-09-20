<?php

namespace EscolaLms\Core;

use EscolaLms\Core\Providers\Injectable;
use EscolaLms\Core\Repositories\ConfigRepository;
use EscolaLms\Core\Repositories\Contracts\ConfigRepositoryContract;
use EscolaLms\Core\Services\ConfigService;
use EscolaLms\Core\Services\Contracts\ConfigServiceContract;
use Illuminate\Support\ServiceProvider;

class EscolaLmsServiceProvider extends ServiceProvider
{
    use Injectable;

    private const CONTRACTS = [
        ConfigRepositoryContract::class => ConfigRepository::class,
        ConfigServiceContract::class => ConfigService::class
    ];

    public function register()
    {
        $this->injectContract(self::CONTRACTS);
    }

    public function boot()
    {
        $this->loadConfig();
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrations();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'escola-lms');
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

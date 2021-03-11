<?php

namespace EscolaLms\Core;

use EscolaLms\Core\Providers\Injectable;
use EscolaLms\Core\Repositories\AttachmentRepository;
use EscolaLms\Core\Repositories\ConfigRepository;
use EscolaLms\Core\Repositories\Contracts\AttachmentRepositoryContract;
use EscolaLms\Core\Repositories\Contracts\ConfigRepositoryContract;
use EscolaLms\Core\Services\AttachmentService;
use EscolaLms\Core\Services\ConfigService;
use EscolaLms\Core\Services\Contracts\AttachmentServiceContract;
use EscolaLms\Core\Services\Contracts\ConfigServiceContract;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class EscolaLmsServiceProvider extends ServiceProvider
{
    use Injectable;

    private const CONTRACTS = [
        AttachmentServiceContract::class => AttachmentService::class,
        AttachmentRepositoryContract::class => AttachmentRepository::class,
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
        $this->registerComponents();
    }

    private function registerComponents(): void
    {
        Blade::componentNamespace('EscolaSoft\\EscolaLms\\View\\Components\\Forms', 'escola-form');
    }

    private function loadConfig(): void
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('escolalms/core.php')
        ], 'escolalms');

        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'escolalms.core'
        );
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

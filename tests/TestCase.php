<?php

namespace EscolaLms\Core\Tests;

use EscolaLms\Core\EscolaLmsServiceProvider;
use EscolaLms\Core\Models\User;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [EscolaLmsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('auth.providers.users.model', User::class);
    }
}

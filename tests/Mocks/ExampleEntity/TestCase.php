<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\EscolaLmsServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            ExampleEntityServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        ExampleEntityMigration::run();
    }
}

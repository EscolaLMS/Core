<?php

namespace EscolaLms\Core\Tests\Features;

use EscolaLms\Core\Repositories\ConfigRepository;
use EscolaLms\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// TODO: database.options doesn't exists, does it mean that ConfigRepository can be removed from Core? Is this unused?
class ConfigRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected ConfigRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ConfigRepository::class);
    }

    /*
    public function testConfigRepository()
    {
        $config = $this->repository->getOption('test-code', 'test-key');
        $this->assertNull($config);

        $this->repository->save('test-code', [
            'test-key' => 'test-value',
            'test-key-2' => 'test-value-2'
        ]);

        $config = $this->repository->getOption('test-code', 'test-key');
        $this->assertEquals('test-value', $config->option_value);

        $config = $this->repository->getOptions('test-code');

        $this->assertEquals([
            'test-key' => 'test-value',
            'test-key-2' => 'test-value-2'
        ], $config);
    } 
    */
}

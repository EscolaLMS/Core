<?php

namespace EscolaLms\Core\Tests\Api;

use EscolaLms\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HealthCheckTest extends TestCase
{
    use DatabaseTransactions;

    public function testHealthCheck(): void
    {
        $this->get('/api/core/health-check')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'db_status',
                    'redis_status',
                    'cpu_usage',
                    'disk' => [
                        'free_space',
                        'total_space',
                        'used_space',
                        'percentage_usage',
                        'status',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'db_status' => 'OK',
            ])
            ->assertJsonFragment([
                'status' => 'OK',
            ]);
    }
}

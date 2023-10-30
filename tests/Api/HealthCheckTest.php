<?php

namespace EscolaLms\Core\Tests\Api;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
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
                'redis_status' => 'OK',
            ])
            ->assertJsonFragment([
                'status' => 'OK',
            ]);
    }
}

<?php

namespace EscolaLms\Core\Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CoreControllerTest extends TestCase
{
    use CreatesUsers;
    use DatabaseTransactions;

    public function testPackages()
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'api')->json('GET', 'api/core/packages');
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'escolalms/core'
            ],
        ]);
    }
}

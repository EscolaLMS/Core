<?php

namespace EscolaLms\Core\Tests\Api;

use Carbon\Carbon;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CoreControllerTest extends TestCase
{
    use CreatesUsers;
    use DatabaseTransactions;

    public function testPackages(): void
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

    public function testSetTimezoneForUsers(): void
    {
        $this->withMiddleware();
        $admin = $this->makeAdmin();
        $timezone = 'Europe/Warsaw';
        $response = $this->actingAs($admin, 'api')->json('GET', 'api/core/packages', [], [
            'CURRENT_TIMEZONE' => $timezone
        ]);
        $response->assertOk();
        $admin->refresh();
        $this->assertTrue($admin->current_timezone === $timezone);
        $timezone = 'America/New_York';
        $response = $this->actingAs($admin, 'api')->json('GET', 'api/core/packages', [], [
            'CURRENT_TIMEZONE' => $timezone
        ]);
        $response->assertOk();
        $admin->refresh();
        $this->assertTrue($admin->current_timezone === $timezone);
    }
}

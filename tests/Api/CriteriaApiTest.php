<?php

namespace EscolaLms\Core\Tests\Api;

use Carbon\Carbon;
use EscolaLms\Core\Enums\StatusEnum;
use EscolaLms\Core\Tests\Mocks\ExampleEntity\ExampleEntity;
use EscolaLms\Core\Tests\Mocks\ExampleEntity\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class CriteriaApiTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function testPagination(): void
    {
        ExampleEntity::factory()
            ->count(10)
            ->create();

        $response = $this->json('GET', 'api/core?limit=5');
        $response->assertJsonCount(5, 'data');
    }

    public function testDateCriterion(): void
    {
        $date = Carbon::now()->addDays(5);

        ExampleEntity::factory()
            ->count(5)
            ->create(['created_at' => $date]);

        ExampleEntity::factory()
            ->count(5)
            ->create();

        $response = $this->json('GET', 'api/core?gt_created_at=' . $date);

        $response->assertJsonCount(5, 'data');
    }

    public function testLikeCriterion(): void
    {
        ExampleEntity::factory()
            ->count(5)
            ->create(['name' => 'simple name']);

        ExampleEntity::factory()
            ->count(5)
            ->create(['name' => $this->faker->word]);

        $response = $this->json('GET', 'api/core?name=simple');
        $response->assertJsonCount(5, 'data');
    }

    public function testInCriterion(): void
    {
        $ids = ExampleEntity::factory()
            ->count(5)
            ->create()
            ->pluck('id')
            ->map(fn ($item) => 'ids[]=' . $item . '&');

        ExampleEntity::factory()
            ->count(5)
            ->create();

        $response = $this->json('GET', 'api/core?' . $ids->implode(''));
        $response->assertJsonCount(5, 'data');
    }

    public function testIsNullCriterion(): void
    {
        ExampleEntity::factory()
            ->count(5)
            ->create();

        ExampleEntity::factory()
            ->count(5)
            ->create(['description' => null]);

        $response = $this->json('GET', 'api/core?nullable=1');
        $response->assertJsonCount(5, 'data');
    }

    public function testEqualsCriterion(): void
    {
        ExampleEntity::factory()
            ->count(5)
            ->create(['status' => StatusEnum::INACTIVE]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['status' => StatusEnum::ACTIVE]);

        $response = $this->json('GET', 'api/core?status=' . StatusEnum::ACTIVE);
        $response->assertJsonCount(5, 'data');
    }

    public function testPeriodCriterion(): void
    {
        $dateFrom = Carbon::now();
        $dateTo = Carbon::now()->addDays(5);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => Carbon::now()->subYear()]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => $dateFrom]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => $dateTo]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => Carbon::now()->addYear()]);

        $response = $this->json('GET', 'api/core?from=' . $dateFrom . '&to=' . $dateTo);
        $response->assertJsonCount(10, 'data');
    }

    public function testPeriodDto(): void
    {
        $dateFrom = Carbon::now();
        $dateTo = Carbon::now()->addDays(5);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => Carbon::now()->subYear()]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => $dateFrom]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => $dateTo]);

        ExampleEntity::factory()
            ->count(5)
            ->create(['date_time' => Carbon::now()->addYear()]);

        $response = $this->json('GET', 'api/core/period?from=' . $dateFrom . '&to=' . $dateTo);
        $response->assertJsonCount(10);
    }
}

<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExampleEntityFactory extends Factory
{
    protected $model = ExampleEntity::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->words(3, true),
            'status' => $this->faker->randomElement(StatusEnum::asArray()),
            'date_time' => $this->faker->dateTimeBetween(),
        ];
    }
}

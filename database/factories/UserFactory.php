<?php

namespace Database\Factories\EscolaLms\Core\Models;

use Carbon\Carbon;
use EscolaLms\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make("secret"), // secret
            'is_active' => true,
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ];
    }
}

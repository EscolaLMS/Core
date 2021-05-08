<?php

namespace EscolaLms\Core\Tests;

use EscolaLms\Core\Enums\UserRole;
use Illuminate\Contracts\Auth\Authenticatable as User;

trait CreatesUsers
{
    private function makeStudent(array $data = [], bool $create = true): User
    {
        $user = $this->create($data, $create);
        $user->assignRole(UserRole::STUDENT);
        return $user;
    }

    private function makeInstructor(array $data = [], bool $create = true): User
    {
        $user = $this->create($data, $create);
        $user->assignRole(UserRole::TUTOR);
        return $user;
    }

    private function makeAdmin(array $data = [], bool $create = true): User
    {
        $user = $this->create($data, $create);
        $user->assignRole(UserRole::ADMIN);
        return $user;
    }

    private function create(array $data = [], bool $create = true): User
    {
        if (!$create) {
            return config('auth.providers.users.model')::factory()->make($data);
        }

        return config('auth.providers.users.model')::factory()->create($data);
    }
}

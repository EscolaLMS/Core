<?php

namespace EscolaLms\Core\Tests;

use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Models\User;

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
        $user->assignRole(UserRole::INSTRUCTOR);
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
            return User::factory()->make($data);
        }

        return User::factory()->create($data);
    }
}

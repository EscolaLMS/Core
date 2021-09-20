<?php

namespace EscolaLms\Core\Tests\Repositories;

use EscolaLms\Core\Models\User;
use EscolaLms\Core\Repositories\BaseRepository as CoreBaseRepository;

class BaseRepository extends CoreBaseRepository
{
    public function model(): string
    {
        return User::class;
    }

    public function getFieldsSearchable(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
        ];
    }
}

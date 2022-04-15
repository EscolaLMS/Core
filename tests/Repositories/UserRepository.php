<?php

namespace EscolaLms\Core\Tests\Repositories;

use EscolaLms\Core\Models\User;
use EscolaLms\Core\Repositories\BaseRepository;
use EscolaLms\Core\Repositories\Traits\Activationable;

class UserRepository extends BaseRepository
{
    use Activationable;

    protected array $fieldSearchable = [];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }
}

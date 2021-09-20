<?php

namespace EscolaLms\Core\Tests\Repositories;

use EscolaLms\Core\Dtos\Contracts\CompareDtoContract;

class CompareDto extends UpdateDto implements CompareDtoContract
{
    public function identifier(): array
    {
        return [
            'email' => $this->email
        ];
    }
}

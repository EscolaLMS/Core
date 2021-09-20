<?php

namespace EscolaLms\Core\Tests\Mocks;

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

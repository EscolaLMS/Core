<?php

namespace EscolaLms\Core\Repositories\Criteria;

use EscolaLms\Core\Repositories\Criteria\Primitives\EqualCriterion;

class FileTagCriterion extends EqualCriterion
{
    public function __construct(string $value, string $key = 'file_tag')
    {
        parent::__construct($key, $value);
    }
}

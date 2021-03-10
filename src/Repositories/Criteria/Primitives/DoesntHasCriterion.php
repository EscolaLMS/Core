<?php


namespace EscolaLms\Core\Repositories\Criteria\Primitives;

use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class DoesntHasCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->whereDoesntHave($this->key, $this->value);
    }
}

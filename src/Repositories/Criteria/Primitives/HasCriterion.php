<?php


namespace EscolaLms\Core\Repositories\Criteria\Primitives;

use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class HasCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->whereHas($this->key, $this->value);
    }
}

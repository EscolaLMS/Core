<?php

namespace EscolaLms\Core\Repositories\Criteria\Primitives;

use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class WhereNotInOrIsNullCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->orWhereNotIn($this->key, $this->value);
            $query->orWhereNull($this->key);
        });
    }
}

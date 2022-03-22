<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Dtos\PeriodDto;
use EscolaLms\Core\Repositories\BaseRepository as CoreBaseRepository;
use EscolaLms\Core\Repositories\Criteria\PeriodCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\DateCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\EqualCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\InCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\IsNullCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\LikeCriterion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExampleEntityRepository extends CoreBaseRepository
{
    public function getFieldsSearchable(): array
    {
        return [];
    }

    public function model(): string
    {
        return ExampleEntity::class;
    }

    public function searchAndPaginateByCriteria(
        ExampleEntitySearchDto $criteria,
        ?PaginationDto $paginationDto = null,
        ?OrderDto $orderDto = null
    ): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query = $this->applyCriteria($query, $criteria->toArray());

        return $query
            ->orderBy($orderDto->getOrderBy() ?? 'id', $orderDto->getOrder() ?? 'asc')
            ->paginate($paginationDto->getLimit());
    }
}

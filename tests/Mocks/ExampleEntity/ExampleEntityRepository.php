<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Repositories\BaseRepository as CoreBaseRepository;
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

<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Dtos\PeriodDto;
use EscolaLms\Core\Repositories\Criteria\PeriodCriterion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExampleEntityService
{
    private ExampleEntityRepository $repository;

    public function __construct(ExampleEntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByPeriod(PeriodDto $periodDto): Collection
    {
        return $this->repository->searchByCriteria([
            new PeriodCriterion($periodDto->from(), $periodDto->to(), 'date_time')
        ]);
    }

    public function getExampleEntities(ExampleEntitySearchDto $searchDto, PaginationDto $paginationDto, OrderDto $orderDto): LengthAwarePaginator
    {
        return $this->repository->searchAndPaginateByCriteria(
            $searchDto,
            $paginationDto,
            $orderDto
        );
    }
}

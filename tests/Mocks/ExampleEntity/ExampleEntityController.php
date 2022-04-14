<?php
namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Dtos\PeriodDto;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExampleEntityController extends EscolaLmsBaseController
{
    private ExampleEntityService $service;

    public function __construct(ExampleEntityService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $orderDto = OrderDto::instantiateFromRequest($request);
        $paginationDto = PaginationDto::instantiateFromRequest($request);
        $searchDto = ExampleEntitySearchDto::instantiateFromRequest($request);

        $results = $this->service->getExampleEntities($searchDto, $paginationDto, $orderDto);

        return new JsonResponse($results);
    }

    public function period(Request $request): JsonResponse
    {
        $periodDto = PeriodDto::instantiateFromRequest($request);

        $results = $this->service->getByPeriod($periodDto);

        return new JsonResponse($results);
    }
}

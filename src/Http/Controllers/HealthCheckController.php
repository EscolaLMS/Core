<?php

namespace EscolaLms\Core\Http\Controllers;

use EscolaLms\Core\Http\Controllers\Swagger\HealthCheckSwagger;
use EscolaLms\Core\Services\Contracts\HealthCheckServiceContract;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends EscolaLmsBaseController implements HealthCheckSwagger
{
    public function __construct(private HealthCheckServiceContract $healthCheckService)
    {
    }

    public function healthCheck(): JsonResponse
    {
        return $this->sendResponse($this->healthCheckService->getHealthData());
    }
}

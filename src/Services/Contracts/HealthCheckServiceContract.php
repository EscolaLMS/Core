<?php

namespace EscolaLms\Core\Services\Contracts;

interface HealthCheckServiceContract
{
    public function getHealthData(): array;
}

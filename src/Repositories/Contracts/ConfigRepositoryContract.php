<?php

namespace EscolaLms\Core\Repositories\Contracts;

use EscolaLms\Core\Models\Config;

interface ConfigRepositoryContract
{
    public function save(string $code, array $options): void;

    public function getOption(string $code, string $key): ?Config;

    public function getOptions(string $code): array;
}

<?php

namespace EscolaLms\Core\Services\Contracts;

interface ConfigServiceContract
{
    public function save(string $code, array $input, array $files): void;

    public function getOptions(string $code): array;

    public function getOption(string $code = '', string $key = ''): string;
}

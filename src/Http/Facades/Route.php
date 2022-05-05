<?php

namespace EscolaLms\Core\Http\Facades;

class Route extends \Illuminate\Support\Facades\Route
{
    private static array $middlewares = [];

    public static function extend(string $middleware): void
    {
        self::$middlewares[] = $middleware;
    }

    public static function apply(array $fields): array
    {
        foreach ($fields as $middleware) {
            self::$middlewares[] = $middleware;
        }
        return array_unique(self::$middlewares);
    }
}

<?php

namespace EscolaLms\Core\Models\Traits;

use Rennokki\QueryCache\Traits\QueryCacheable as BaseQueryCacheable;

trait QueryCacheable
{
    use BaseQueryCacheable;

    public $cacheFor = 3600;
}

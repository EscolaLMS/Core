<?php

namespace EscolaLms\Core\Providers;

/**
 * @deprecated 1.1.0
 */
trait Injectable
{
    private function injectContract(array $contracts): void
    {
        foreach ($contracts as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}

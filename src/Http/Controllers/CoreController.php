<?php

namespace EscolaLms\Core\Http\Controllers;

use Composer\InstalledVersions;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use Illuminate\Support\Str;

/**
 * @OA\Info(title="EscolaLMS", version="0.0.1")
 *
 * @OA\SecurityScheme(
 *      securityScheme="passport",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 */
class CoreController extends EscolaLmsBaseController
{

    public function packages()
    {
        $escolaLmsPackagesWithVersions = array_reduce(
            array_filter(InstalledVersions::getInstalledPackages(), fn (string $package) => Str::startsWith($package, 'escolalms/')),
            fn (array $accumulator, string $package) => array_merge($accumulator, [$package => InstalledVersions::getPrettyVersion($package)]),
            []
        );
        return $this->sendResponse($escolaLmsPackagesWithVersions);
    }
}

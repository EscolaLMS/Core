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

    /**
     * @OA\Get(
     *      path="/api/core/packages",
     *      summary="List installed escolalms packages",
     *      tags={"Core Admin"},
     *      security={
     *          {"passport": {}},
     *      },
     *      description="List of installed escolalms packages with versions",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          ),
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(@OA\Schema(type="string"))
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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

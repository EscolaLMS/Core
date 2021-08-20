<?php

namespace EscolaLms\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

/**
 * @OA\Info(title="Get Kibble", version="0.0.1")
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

class EscolaLmsBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse(mixed $data, string $message, int $code = 200): JsonResponse
    {
        $body = [
            'success' => $code >= 200 && $code < 300,
            'message' => $message,
        ];
        if (!is_null($data)) {
            $body['data'] = $data;
        }
        return Response::json($body, $code);
    }

    public function sendError(string $error, int $code = 404): JsonResponse
    {
        return $this->sendResponse(null, $error, $code);
    }

    public function sendSuccess(string $message, int $code = 200): JsonResponse
    {
        return $this->sendResponse(null, $message, $code);
    }

    public function sendResponseForResource(JsonResource $resource, string $message = ''): JsonResponse
    {
        $request = request();
        $wrappedResource = $resource->resource;
        if ($wrappedResource instanceof LengthAwarePaginator) {
            $meta = $wrappedResource->toArray();
            unset($meta['data']);
            return $this->sendResponseWithMeta($resource->toArray($request), $meta, $message);
        }
        if ($wrappedResource instanceof Model && $wrappedResource->wasRecentlyCreated) {
            return $this->sendResponse($resource->toArray($request), $message, 201);
        }
        return $this->sendResponse($resource->toArray($request), $message);
    }

    public function sendResponseWithMeta(array $data, array $meta, string $message = '', int $code = 200): JsonResponse
    {
        return Response::json([
            'success' => $code >= 200 && $code < 300,
            'data'    => $data,
            'meta' => $meta,
            'message' => $message,
        ], $code);
    }
}

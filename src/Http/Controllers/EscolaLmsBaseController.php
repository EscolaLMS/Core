<?php

namespace EscolaLms\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

abstract class EscolaLmsBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($data, string $message = '', int $code = 200): JsonResponse
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

    public function sendError(string $error = '', int $code = 404): JsonResponse
    {
        return $this->sendResponse(null, $error, $code);
    }

    public function sendSuccess(string $message = '', int $code = 200): JsonResponse
    {
        return $this->sendResponse(null, $message, $code);
    }

    public function sendResponseForResource(JsonResource $resource, string $message = ''): JsonResponse
    {
        $request = request();
        $wrappedResource = $resource->resource;
        if ($wrappedResource instanceof LengthAwarePaginator) {
            return $this->sendResponseForWrappedPaginator($request, $resource, $message);
        }
        if ($wrappedResource instanceof Model && $wrappedResource->wasRecentlyCreated) {
            return $this->sendResponse($resource->toArray($request), $message, 201);
        }
        return $this->sendResponse($resource->toArray($request), $message);
    }

    private function sendResponseForWrappedPaginator(Request $request, JsonResource $resource, string $message = ''): JsonResponse
    {
        $wrappedResource = $resource->resource;
        $meta = $wrappedResource->toArray();
        if ($resource instanceof ResourceCollection) {
            $data = $resource->toArray($request);
        } else {
            $data = $meta['data'];
        }
        unset($meta['data']);
        return $this->sendResponseWithMeta($data, $meta, $message);
    }

    public function sendResponseWithMeta(array $data, array $meta, string $message = '', int $code = 200): JsonResponse
    {
        return Response::json([
            'success' => $code >= 200 && $code < 300,
            'data'    => $data,
            'meta'    => $meta,
            'message' => $message,
        ], $code);
    }
}

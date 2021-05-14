<?php

namespace EscolaLms\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class EscolaLmsBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message)
    {
        return Response::json([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ]);
    }

    public function sendError($error, $code = 404)
    {
        return Response::json([
                'success' => false,
                'message' => $error,
            ],
            $code
        );
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
        ]);
    }
}

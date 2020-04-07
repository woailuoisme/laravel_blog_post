<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;

class AppBaseController extends Controller
{
    public function sendResponse($data, $message, $code = 200): \Illuminate\Http\JsonResponse
    {
        return Response::json(ResponseUtil::makeResponse($message, $data), $code);
    }

    public function sendError($error, $code = 400): \Illuminate\Http\JsonResponse
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    public function sendSuccess($message, $code=200): \Illuminate\Http\JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], $code);
    }
}

<?php


namespace App\Http\Service;


use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;

class AppbaseService
{
    public function sendResponse($data, $message, $code = 200): \Illuminate\Http\JsonResponse
    {
        return Response::json(ResponseUtil::makeResponse($message, $data), $code);
    }
    public function sendResponseWithoutMsg($data, $code = 200): \Illuminate\Http\JsonResponse
    {
        return Response::json([
            'success' => true,
            'data'=>$data
        ], $code);
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

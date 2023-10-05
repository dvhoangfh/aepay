<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Services\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse(string $message = '', array $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            's' => true,
            'msg' => $message,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $message, array $errors = [], int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            's' => false,
            'msg' => $message,
            'errors' => $errors
        ];

        return response()->json($response, $code);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Error message response
     *
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorMessageResponse($message, $statusCode)
    {
        return response()->json([
            'success' => false,
            'message'=> $message
        ], $statusCode);
    }

    /**
     * Success message response
     *
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successMessageResponse($message, $statusCode)
    {
        return response()->json([
            'success' => true,
            'message'=> $message
        ], $statusCode);
    }
}

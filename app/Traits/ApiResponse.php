<?php
// app/Traits/ApiResponse.php

namespace App\Traits;

trait ApiResponse
{
    private function successResponse($message, $data = null, $code = 200)
    {
        return response()->json([
            'message' => $message,
            'state' => true,
            'data' => $data
        ], $code);
    }

    private function errorResponse($message, $error = null, $code = 500)
    {
        return response()->json([
            'message' => $message,
            'state' => false,
            'error' => $error
        ], $code);
    }
}

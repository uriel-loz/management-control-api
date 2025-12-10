<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait {
    protected function successResponse(
        $data = null, 
        $message = 'Operación exitosa', 
        $code = 200
    ): JsonResponse {
        return response()->json([
            'code' => $code,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(
        $message = 'Error en la operación', 
        $code = 500, 
        $errors = null, 
        $line = null
    ): JsonResponse {
        $response = [
            'code' => $code,
            'status' => 'error',
            'message' => $message,
            'line' => $line,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
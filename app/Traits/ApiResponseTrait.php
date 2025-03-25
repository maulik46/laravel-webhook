<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait ApiResponseTrait
{
    protected function errorResponse(string $message, int $statusCode): JsonResponse
    {
        // Log the error message
        Log::error($message);

        return response()->json([
            'message' => 'Webhook processing failed',
            'error' => $message
        ], $statusCode);
    }

    protected function successResponse(string $message, array $result, int $statusCode): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $result
        ], $statusCode);
    }
}

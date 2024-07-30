<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function jsonResponse(string $message, mixed $data = [], int $status = 200): JsonResponse
    {
        return response()->json(['message' => $message, 'data' => $data], $status);
    }
}

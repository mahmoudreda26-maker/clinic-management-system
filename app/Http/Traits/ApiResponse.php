<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    protected function paginated(
        LengthAwarePaginator $paginator,
        string $resourceClass,
        string $message = 'Data retrieved successfully'
    ): JsonResponse {
        $result = $resourceClass::collection($paginator)
            ->response()
            ->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $result['data'],
            'meta' => $result['meta'] ?? [],
            'links' => $result['links'] ?? [],
        ]);
    }
}

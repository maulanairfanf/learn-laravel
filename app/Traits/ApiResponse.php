<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Format the API response.
     *
     * @param  mixed $data
     * @param  string $message
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function formatApiResponse($data, $message = '', $status = 200): JsonResponse
    {
        return response()->json([
            'status' => $status === 200 ? 'success' : 'error',
            'message' => $message ?: $this->getDefaultMessage($status),
            'data' => $data,
        ], $status);
    }

    /**
     * Get the default message for a given status code.
     *
     * @param int $status
     * @return string
     */
    protected function getDefaultMessage($status): string
    {
        $messages = [
            200 => 'Request successful',
            201 => 'Resource created successfully',
            204 => 'No content',
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Resource not found',
            500 => 'Internal server error',
        ];

        return $messages[$status] ?? 'Unknown status';
    }
}

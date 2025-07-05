<?php

namespace App\Helpers;

class ApiResponseHelper
{
    /**
     * Generate a standardized success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Generate a standardized error response.
     *
     * @param string $message
     * @param int $status
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = 'Error', $status = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * @param mixed $paginatedData
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */

    public static function paginate($paginatedData, $status = 200)
    {
        return response()->json([
            'success' => true,
            ...$paginatedData
        ], $status);
    }
}

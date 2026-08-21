<?php

namespace App\Helpers;

class XhrResponse
{
    /**
     * Create a JSON success response.
     *
     * @param array $data The response data.
     * @param string $message The success message.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = array(), $message = 'success')
    {
        return response()->json(['success' => true, 'data' => $data, 'message' => $message]);
    }

    /**
     * Create a JSON error response.
     *
     * @param array $data The response data.
     * @param string $message The error message.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(array $data = array(), string $message = 'error', $statusCode = 422)
    {
        return response()->json(['success' => false, 'data' => $data, 'message' => $message]);
    }
}

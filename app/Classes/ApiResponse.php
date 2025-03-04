<?php

namespace App\Classes;

class ApiResponse
{
    public static function sendResponse($result, $message, $code = 200, $token='') 
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
}
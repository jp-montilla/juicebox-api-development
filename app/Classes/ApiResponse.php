<?php

namespace App\Classes;

class ApiResponse
{
    public static function sendResponse($result, $message, $code, $token='') 
    {
        $response = [
            'data'    => $result,
            'message' => $message,
        ];

        if ($token !== '')
        {
            $response['token'] = $token;
        }

        return response()->json($response, $code);
    }
}

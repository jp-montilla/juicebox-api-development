<?php

namespace App\Classes;

class LoginResponse
{
    public static function sendResponse($result, $message, $token, $code = 200) 
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'token' => $token,
        ];

        return response()->json($response, $code);
    }
}

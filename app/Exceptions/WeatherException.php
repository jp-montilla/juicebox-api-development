<?php

namespace App\Exceptions;

use App\Services\CachingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WeatherException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        if (config('constants.weather_third_party_api') == 'openweathermap') {
            $response = json_decode(substr($this->getMessage(), strpos($this->getMessage(), "response") + 10));
        }
        
        return new JsonResponse([
            'errors' => [
                'message' => $response->message,
            ]
        ], $response->cod);
    }
}

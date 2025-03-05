<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Interfaces\WeatherForecastInterface;

class WeatherController extends Controller
{
    public function __construct(private WeatherForecastInterface $weatherForecastInterface)
    {
        
    }

    public function getWeather()
    {
        $weather = $this->weatherForecastInterface->fetchWeather(config('constants.weather_default_city'));

        return ApiResponse::sendResponse($weather, 'Weather Retrieved!', 200);
    }
}

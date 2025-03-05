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

    public function getWeather(string $city = 'Perth')
    {
        $weather = $this->weatherForecastInterface->fetchWeather($city);

        return ApiResponse::sendResponse($weather, 'Weather Retrieved!', 200);
    }
}

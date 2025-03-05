<?php

namespace App\Services;

use App\Interfaces\WeatherForecastInterface;
use Illuminate\Support\Facades\Cache;

class OpenWeatherForecastService implements WeatherForecastInterface
{
    public function __construct(private ExternalApiService $externalApiService)
    {

    }

    public function fetchWeather(string $city)
    {
        $apiUrl = config('constants.weather_api_url').'?q='.$city.'&appId='.config('constants.weather_api_key');
        $weather = Cache::remember('users', now()->addMinutes(15), function () use ($apiUrl) {
            return $this->formatWeatherResponse($this->externalApiService->callThirdyPartyApi($apiUrl));
        });
        return $weather;
    }

    private function formatWeatherResponse($weather)
    {
        return [
            'date' => date("Y-m-d H:i:s", substr($weather['dt'], 0, 10)),
            'city' => $weather['name'],
            'country' => $weather['sys']['country'],
            'weather' => [
                            'main' => $weather['weather'][0]['main'],
                            'description' => $weather['weather'][0]['description'],
                            'temperature' => $weather['main']['temp'],
                        ],
        ];
    }
}

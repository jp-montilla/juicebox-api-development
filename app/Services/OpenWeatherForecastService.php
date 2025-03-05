<?php

namespace App\Services;

use App\Interfaces\WeatherForecastInterface;
use Illuminate\Support\Facades\Cache;

class OpenWeatherForecastService implements WeatherForecastInterface
{
    public function __construct(private ExternalApiService $externalApiService,)
    {

    }

    public function fetchWeather(string $city)
    {
        $apiUrl = config('constants.weather_api_url').'?q='.$city.'&appId='.config('constants.weather_api_key');
        $cacheName = config('constants.weather_cache_key').'_'.$apiUrl;

        return Cache::remember($cacheName, now()->addMinutes(15), function() use ($apiUrl) {
            return $this->formatWeatherResponse($this->externalApiService->callThirdyPartyApi($apiUrl, [], 'GET', config('constants.weather_cache_key')));
        });
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

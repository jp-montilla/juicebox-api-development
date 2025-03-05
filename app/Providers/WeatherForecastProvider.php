<?php

namespace App\Providers;

use App\Interfaces\WeatherForecastInterface;
use App\Services\OpenWeatherForecastService;
use Illuminate\Support\ServiceProvider;

class WeatherForecastProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (config('constants.weather_third_party_api') == 'openweathermap') {
            $this->app->bind(WeatherForecastInterface::class, OpenWeatherForecastService::class);
        } 
    }
}

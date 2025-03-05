<?php

namespace App\Interfaces;

interface WeatherForecastInterface
{
    public function fetchWeather(string $city);
}

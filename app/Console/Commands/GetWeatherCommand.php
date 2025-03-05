<?php

namespace App\Console\Commands;

use App\Interfaces\WeatherForecastInterface;
use Illuminate\Console\Command;

class GetWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:forecast {city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get weather forecast';

    /**
     * Execute the console command.
     */
    public function handle(WeatherForecastInterface $weatherForecastInterface)
    {
        $weatherForecastInterface->fetchWeather($this->argument('city'));
    }
}

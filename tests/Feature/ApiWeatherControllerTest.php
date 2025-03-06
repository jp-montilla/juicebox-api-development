<?php

namespace Tests\Feature;

use App\Interfaces\WeatherForecastInterface;
use App\Services\ExternalApiService;
use Mockery\MockInterface;
use Tests\TestCase;

class ApiWeatherControllerTest extends TestCase
{

    public function test_weather_api_call_response()
    {
        $expectedResponse = [
            'date' => '2025-03-06 11:20:36',
            'city' => 'Perth',
            'country' => 'AU',
            'weather' => [
                'main' => 'Clear',
                'description' => 'clear sky',
                'temperature' => 304.2
            ]
        ];

        $this->mock(WeatherForecastInterface::class, function(MockInterface $mock) use ($expectedResponse) {
            $mock->shouldReceive('fetchWeather')->once()->andReturn($expectedResponse);
        });

        $this->getJson('api/weather')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'date',
                'city',
                'country',
                'weather' => [
                    'main',
                    'description',
                    'temperature',
                ]
            ]
        ])
        ->assertJson([
            'message' => 'Weather Retrieved!',
        ]);
    }

    public function test_external_api_call()
    {
        $apiUrl = config('constants.weather_api_url').'?q=Perth&appId='.config('constants.weather_api_key');
        $additionalHeaders = [];
        $method = 'GET';
        $cacheName = config('constants.weather_cache_key');


        $this->mock(ExternalApiService::class, function(MockInterface $mock) use ($apiUrl, $additionalHeaders, $method, $cacheName) {
            $mock->shouldReceive('callThirdyPartyApi')->with($apiUrl, $additionalHeaders, $method, $cacheName)
            ->once()
            ->andReturn([
                'coord' => [
                    'lon' => 115.8333,
                    'lat' => -31.9333
                ],
                'weather' => [
                    [
                        'id' => 800,
                        'main' => 'Clear',
                        'description' => 'clear sky',
                        'icon' => '01n'
                    ]
                ],
                'base' => 'stations',
                'main' => [
                    'temp' => 304.19,
                    'feels_like' => 303.12,
                    'temp_min' => 303.73,
                    'temp_max' => 305.15,
                    'pressure' => 1013,
                    'humidity' => 32,
                    'sea_level' => 1013,
                    'grnd_level' => 1010
                ],
                'visibility' => 10000,
                'wind' => [
                    'speed' => 5.66,
                    'deg' => 210
                ],
                'clouds' => [
                    'all' => 0
                ],
                'dt' => 1741260517,
                'sys' => [
                    'type' => 2,
                    'id' => 63154,
                    'country' => 'AU',
                    'sunrise' => 1741212593,
                    'sunset' => 1741257984
                ],
                'timezone' => 28800,
                'id' => 2063523,
                'name' => 'Perth',
                'cod' => 200
            ]);
        });

        $this->getJson('api/weather')
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Weather Retrieved!',
        ]);
    }
}

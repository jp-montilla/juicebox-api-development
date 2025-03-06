<?php

return [
    'auth_provider' => env('AUTH_PROVIDER', 'sanctum'),

    'weather_third_party_api' => env('WEATHER_MAP_THIRD_PARTY_API', 'openweathermap'),
    'weather_api_url' => env('WEATHER_MAP_API_URL', 'api_url'),
    'weather_api_key' => env('WEATHER_MAP_API_KEY', 'api_key'),
    'weather_cache_key' => env('WEATHER_CACHE_KEY', 'weather_forecast'),
    'weather_default_city' => env('DEFAULT_WEATHER_CITY', 'default_city'),
];
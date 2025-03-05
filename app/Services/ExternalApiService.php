<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Exceptions\WeatherException;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ExternalApiService
{

    public function callThirdyPartyApi(string $apiUrl, array $additionalHeaders = [], string $method = 'GET', string $cacheName = '')
    {
        $headers = [
            'Accept' => 'application/json',
        ];
        $headers = array_merge($headers, $additionalHeaders);
        $client = new Client();

        try
        {
            $response = $client->request($method, $apiUrl, [
                'headers' => $headers,
            ]);
            $formattedResponse = json_decode($response->getBody(), true);

            return $formattedResponse;
    
        }
        catch (Exception $e)
        {
            if ($cacheName === config('constants.weather_cache_key')) {
                throw new WeatherException($e->getMessage());
            }

            throw new CustomException($e->getMessage(), $e->getCode());
        }
    }
}

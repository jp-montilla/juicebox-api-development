<?php

namespace App\Services;

use App\Exceptions\CustomException;
use Exception;
use GuzzleHttp\Client;

class ExternalApiService
{
    public function callThirdyPartyApi(string $apiUrl, array $additionalHeaders = [], string $method = 'GET')
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
            throw new CustomException('City not found!', 404);
        }
    }
}

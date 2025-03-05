<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            $response = [
                'message' => 'City not found',
            ];

            throw new HttpResponseException(response()->json($response, 404));
        }
    }
}

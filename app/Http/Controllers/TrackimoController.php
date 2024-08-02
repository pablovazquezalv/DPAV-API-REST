<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class TrackimoController extends Controller
{
    private $trackimoServerUrl = 'https://app.trackimo.com';
    private $clientId = '2c205218-b2e7-4d9a-b9a3-8ff6cb6f747b';
    private $clientSecret = 'bfa6b9141686e404e39ed90860dea901';
    private $redirectUri = 'http://134.209.35.1/api/oauth2/handler';

    
    public function login()
    {
        try {
            $client = new Client();
            
            // Login request
            $response = $client->post("{$this->trackimoServerUrl}/api/internal/v2/user/login", [
                'json' => [
                    'username' => 'pabloalvaradovazquez10@gmail.com',
                    'password' => 'Juventud12@'
                ]
            ]);

            $cookies = $response->getHeader('Set-Cookie');

            // Authorization request
            $authUri = "{$this->trackimoServerUrl}/api/v3/oauth2/auth?client_id={$this->clientId}&redirect_uri={$this->redirectUri}&response_type=code&scope=locations,notifications,devices,accounts,settings,geozones";
            $authResponse = $client->get($authUri, [
                'headers' => [
                    'Cookie' => implode('; ', $cookies)
                ]
            ]);

            // Get code
            $codeResponse = Http::get('http://134.209.35.1/api/get-code');
            $code = $codeResponse->json('code');

            // Get token
            $tokenResponse = $client->post("{$this->trackimoServerUrl}/api/v3/oauth2/token", [
                'json' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $code
                ],
                'headers' => [
                    'Cookie' => implode('; ', $cookies)
                ]
            ]);

            $data = json_decode($tokenResponse->getBody(), true);
            return response()->json([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token']
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerUltimaUbicacion(Request $request)
    {
        try {
            $token = 'f54ac611-787f-4460-acd6-bda123d3b8a7';
            $client = new Client();
            $response = $client->post("{$this->trackimoServerUrl}/api/v3/accounts/1311342/locations/filter?limit=2", [
                'json' => [
                    'device_ids' => [601458260],
                    'forceGpsRead' => true,
                    'sendGsmBeforeLock' => true
                ],
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

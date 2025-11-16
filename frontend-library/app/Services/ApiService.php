<?php

namespace App\Services;

use Config\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiService
{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = new Api();
        $this->client = new Client([
            'base_uri' => $this->config->baseURL,
            'timeout'  => $this->config->timeout,
            'headers'  => $this->config->headers
        ]);
    }

    /**
     * Send GET request
     */
    public function get($endpoint, $params = [])
    {
        try {
            $response = $this->client->get($endpoint, [
                'query' => $params
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Send POST request
     */
    public function post($endpoint, $data = [])
    {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Send PUT request
     */
    public function put($endpoint, $data = [])
    {
        try {
            $response = $this->client->put($endpoint, [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Send PATCH request
     */
    public function patch($endpoint, $data = [])
    {
        try {
            $response = $this->client->patch($endpoint, [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Send DELETE request
     */
    public function delete($endpoint)
    {
        try {
            $response = $this->client->delete($endpoint);
            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API exceptions
     */
    private function handleException(RequestException $e)
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            
            return [
                'success' => false,
                'error' => 'API Error: ' . $statusCode,
                'message' => json_decode($body, true)['message'] ?? 'Unknown error'
            ];
        }

        return [
            'success' => false,
            'error' => 'Connection Error',
            'message' => 'Tidak dapat terhubung ke server API'
        ];
    }
}

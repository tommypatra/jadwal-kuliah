<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class Sevima
{
    protected string $baseUrl;
    protected array $headers;
    protected int $timeout;

    /*
    |--------------------------------------------------------------------------
    | Retry Delay
    |--------------------------------------------------------------------------
    */
    protected int $retryDelay = 2;
    public function __construct()
    {
        $config = config('sevima');
        $this->baseUrl = rtrim($config['base_url'], '/') . '/';
        $this->timeout = $config['timeout'] ?? 30;
        $this->headers = $config['headers'];
    }

    /*
    |--------------------------------------------------------------------------
    | HTTP METHODS
    |--------------------------------------------------------------------------
    */

    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, $query);
    }

    public function post(string $endpoint, array $body = []): array
    {
        return $this->request('POST', $endpoint, $body);
    }

    public function put(string $endpoint, array $body = []): array
    {
        return $this->request('PUT', $endpoint, $body);
    }

    public function delete(string $endpoint, array $body = []): array
    {
        return $this->request('DELETE', $endpoint, $body);
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN REQUEST
    |--------------------------------------------------------------------------
    */

    protected function request(
        string $method,
        string $endpoint,
        array $payload = []
    ): array {
        while (true) {
            /*
            |--------------------------------------------------------------------------
            | GLOBAL LOCK
            |--------------------------------------------------------------------------
            |
            | Semua request menggunakan shared API key
            | harus antre satu per satu
            |
            */

            return Cache::lock('sevima-api-lock', 10)
                ->block(10, function () use (
                    $method,
                    $endpoint,
                    $payload
                ) {
                    try {
                        $url = $this->baseUrl . ltrim($endpoint, '/');
                        $http = Http::withHeaders($this->headers)
                            ->timeout($this->timeout);
                        $response = match ($method) {
                            'POST' => $http->post($url, $payload),
                            'PUT' => $http->put($url, $payload),
                            'DELETE' => $http->delete($url, $payload),
                            default => $http->get($url, $payload),
                        };

                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS
                        |--------------------------------------------------------------------------
                        */

                        if ($response->successful()) {
                            return $this->formatResponse($response);
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | RATE LIMIT
                        |--------------------------------------------------------------------------
                        */

                        if ($response->status() == 429) {
                            logger()->warning('SEVIMA rate limit hit', [
                                'endpoint' => $endpoint,
                                'wait' => $this->retryDelay,
                            ]);

                            /*
                            |--------------------------------------------------------------------------
                            | Tunggu lalu coba lagi
                            |--------------------------------------------------------------------------
                            */
                            sleep($this->retryDelay);
                            return $this->request(
                                $method,
                                $endpoint,
                                $payload
                            );
                        }
                        /*
                        |--------------------------------------------------------------------------
                        | OTHER ERROR
                        |--------------------------------------------------------------------------
                        */
                        return $this->formatResponse($response);
                    } catch (\Throwable $e) {
                        report($e);
                        return [
                            'success' => false,
                            'status' => 500,
                            'message' => $e->getMessage(),
                            'data' => null,
                        ];
                    }
                });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT RESPONSE
    |--------------------------------------------------------------------------
    */

    protected function formatResponse(Response $response): array
    {
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'message' => $response->successful()
                ? 'Success'
                : $response->body(),

            'data' => $response->json(),
        ];
    }
}
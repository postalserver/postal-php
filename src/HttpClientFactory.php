<?php

declare(strict_types=1);

namespace Postal;

use GuzzleHttp\Client;

class HttpClientFactory
{
    public static function create(string $host, string $apiKey, callable $handler = null): Client
    {
        return new Client([
            'base_uri' => "{$host}/api/v1/",
            'headers' => [
                'X-Server-API-Key' => $apiKey,
            ],
            'handler' => $handler,
        ]);
    }
}

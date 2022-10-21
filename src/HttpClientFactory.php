<?php

declare(strict_types=1);

namespace Postal;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class HttpClientFactory
{
    public static function create(string $host, string $apiKey, callable $handler = null): Client
    {
        if ($handler === null) {
            $handler = HandlerStack::create();
            $handler->push(RetryMiddlewareFactory::build());
        }

        return new Client([
            'base_uri' => "{$host}/api/v1/",
            'headers' => [
                'X-Server-API-Key' => $apiKey,
            ],
            'handler' => $handler,
        ]);
    }
}

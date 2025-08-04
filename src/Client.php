<?php

declare(strict_types=1);

namespace Postal;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    public MessagesService $messages;

    public SendService $send;

    protected HttpClient $httpClient;

    public function __construct(
        string $host,
        string $apiKey,
        ?HttpClient $httpClient = null
    ) {
        $this->httpClient = $httpClient ?: HttpClientFactory::create($host, $apiKey);
        $this->messages = new MessagesService($this);
        $this->send = new SendService($this);
    }

    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    public function prepareResponse(ResponseInterface $response, $class)
    {
        return new $class($this->validateResponse($response));
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return array<T>
     */
    public function prepareListResponse(ResponseInterface $response, $class)
    {
        $list = $this->validateResponse($response);

        // if (! array_is_list($list)) {
        if (! $this->arrayIsList($list)) {
            throw new ApiException('Unexpected response received, expected a list');
        }

        return array_map(fn ($item) => new $class($item), $list);
    }

    /**
     * @return array<string|int, mixed>
     */
    protected function validateResponse(ResponseInterface $response): array
    {
        $json = json_decode((string) $response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($json)) {
            throw new ApiException('Malformed response body received');
        }

        if (! isset($json['status']) || $json['status'] !== 'success') {
            $message = $json['data']['message'] ?? 'An unexpected error was received';
            $code = 0;
            if (isset($json['data']['code'])) {
                $message = $json['data']['code'] . ': ' . $message;
            }

            throw new ApiException($message);
        }

        if (! isset($json['data'])) {
            throw new ApiException('Unexpected response received');
        }

        return $json['data'];
    }

    /**
     * @param array<mixed> $array
     */
    private function arrayIsList(array $array): bool
    {
        $i = 0;
        foreach ($array as $k => $v) {
            if ($k !== $i++) {
                return false;
            }
        }

        return true;
    }
}

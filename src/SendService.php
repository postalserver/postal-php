<?php

declare(strict_types=1);

namespace Postal;

use Postal\Send\Message;
use Postal\Send\RawMessage;
use Postal\Send\Result;

class SendService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function message(Message $message): Result
    {
        return $this->client->prepareResponse(
            $this->client->getHttpClient()->post('send/message', [
                'json' => $message,
            ]),
            Result::class,
        );
    }

    public function raw(RawMessage $message): Result
    {
        return $this->client->prepareResponse(
            $this->client->getHttpClient()->post('send/message', [
                'json' => $message,
            ]),
            Result::class,
        );
    }
}

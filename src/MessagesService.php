<?php

declare(strict_types=1);

namespace Postal;

use Postal\Messages\Delivery;
use Postal\Messages\Message;

class MessagesService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array<string>|true $expansions
     */
    public function details(int $id, $expansions = []): Message
    {
        return $this->client->prepareResponse(
            $this->client->getHttpClient()->post('messages/message', [
                'json' => [
                    'id' => $id,
                    '_expansions' => $expansions,
                ],
            ]),
            Message::class,
        );
    }

    /**
     * @return array<Delivery>
     */
    public function deliveries(int $id): array
    {
        return $this->client->prepareListResponse(
            $this->client->getHttpClient()->post('messages/deliveries', [
                'json' => [
                    'id' => $id,
                ],
            ]),
            Delivery::class,
        );
    }
}

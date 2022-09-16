<?php

declare(strict_types=1);

namespace Postal\Tests;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Postal\Client;
use Postal\Send\Message;
use Postal\Send\RawMessage;

class SendServiceTest extends TestCase
{
    public function testMessage(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'data' => [
                    'message_id' => 'my-message-id',
                    'messages' => [
                        [
                            'id' => 1,
                            'token' => 'A',
                        ],
                        [
                            'id' => 2,
                            'token' => 'B',
                        ],
                    ],
                ],
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);

        $guzzle = new GuzzleHttpClient([
            'handler' => $handlerStack,
        ]);

        $client = new Client('', '', $guzzle);

        $message = new Message();
        $result = $client->send->message($message);

        $this->assertSame('my-message-id', $result->message_id);
        $this->assertCount(2, $result->messages);
        $this->assertSame(1, $result->messages[0]->id);
        $this->assertSame('A', $result->messages[0]->token);
        $this->assertSame(2, $result->messages[1]->id);
        $this->assertSame('B', $result->messages[1]->token);
    }

    public function testRaw(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'data' => [
                    'message_id' => 'my-message-id',
                    'messages' => [
                        [
                            'id' => 1,
                            'token' => 'A',
                        ],
                        [
                            'id' => 2,
                            'token' => 'B',
                        ],
                    ],
                ],
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);

        $guzzle = new GuzzleHttpClient([
            'handler' => $handlerStack,
        ]);

        $client = new Client('', '', $guzzle);

        $message = new RawMessage();
        $result = $client->send->raw($message);

        $this->assertSame('my-message-id', $result->message_id);
        $this->assertCount(2, $result->messages);
        $this->assertSame(1, $result->messages[0]->id);
        $this->assertSame('A', $result->messages[0]->token);
        $this->assertSame(2, $result->messages[1]->id);
        $this->assertSame('B', $result->messages[1]->token);
    }
}

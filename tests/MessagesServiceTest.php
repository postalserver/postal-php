<?php

declare(strict_types=1);

namespace Postal\Tests;

use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Postal\Client;

class MessagesServiceTest extends TestCase
{
    public function testDetails(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'data' => [
                    'id' => 123,
                    'token' => 'abc',
                    'status' => [
                        'status' => 'Held',
                        'last_delivery_attempt' => 1666083425.913461,
                        'held' => true,
                        'hold_expiry' => 1666688225.924133,
                    ],
                    'details' => [
                        'rcpt_to' => 'rcpt_to@example.com',
                        'mail_from' => 'mail_from@example.com',
                        'subject' => 'my subject',
                        'message_id' => '969c4ad7-4cb1-464c-bdfd-14e9995342d3@example.com',
                    ],
                    'inspection' => [
                        'inspected' => true,
                        'spam' => false,
                        'spam_score' => 0,
                        'threat' => false,
                        'threat_details' => null,
                    ],
                    'plain_body' => 'Plain Body',
                    'html_body' => '<p>HTML Body</p>',
                    'attachments' => [
                        [
                            'filename' => 'file.txt',
                            'content_type' => 'text/plain',
                            'data' => 'dGHzdA==',
                            'size' => 4,
                            'hash' => 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',
                        ],
                    ],
                    'headers' => [
                        'from' => [
                            'from@example.com',
                        ],
                    ],
                    'raw_message' => 'raw message',
                ],
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleHttpClient([
            'handler' => $handlerStack,
        ]);

        $client = new Client('', '', $guzzle);

        $details = $client->messages->details(123, true);

        $this->assertSame(123, $details->id);
        $this->assertSame('abc', $details->token);
        $this->assertSame('Held', $details->status['status']);
        $this->assertSame('rcpt_to@example.com', $details->details['rcpt_to']);
        $this->assertTrue($details->inspection['inspected']);
        $this->assertSame('Plain Body', $details->plain_body);
        $this->assertSame('<p>HTML Body</p>', $details->html_body);
        $this->assertSame('file.txt', $details->attachments[0]['filename']);
        $this->assertSame('from@example.com', $details->headers['from'][0]);
        $this->assertSame('raw message', $details->raw_message);
    }

    public function testDeliveries(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'data' => [
                    [
                        'id' => 1,
                        'status' => 'Held',
                        'details' => 'Credential is configured to hold all messages authenticated by it.',
                        'output' => null,
                        'sent_with_ssl' => false,
                        'log_id' => null,
                        'time' => null,
                        'timestamp' => 1666100297,
                    ],
                    [
                        'id' => 2,
                        'status' => 'Sent',
                        'details' => 'Message for test@example.com accepted by mail.protection.outlook.com (0.0.0.0)',
                        'output' => '250',
                        'sent_with_ssl' => false,
                        'log_id' => 'ABCDEF',
                        'time' => 1.15,
                        'timestamp' => 1666100297,
                    ],
                ],
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleHttpClient([
            'handler' => $handlerStack,
        ]);

        $client = new Client('', '', $guzzle);

        $deliveries = $client->messages->deliveries(123);

        $this->assertCount(2, $deliveries);

        $this->assertSame(1, $deliveries[0]->id);
        $this->assertSame('Held', $deliveries[0]->status);
        $this->assertSame('Credential is configured to hold all messages authenticated by it.', $deliveries[0]->details);
        $this->assertNull($deliveries[0]->output);
        $this->assertFalse($deliveries[0]->sent_with_ssl);
        $this->assertNull($deliveries[0]->log_id);
        $this->assertNull($deliveries[0]->time);
        $this->assertInstanceOf(DateTime::class, $deliveries[0]->timestamp);
        $this->assertSame('2022-10-18 13:38:17', $deliveries[0]->timestamp->format('Y-m-d H:i:s'));

        $this->assertSame(2, $deliveries[1]->id);
        $this->assertSame('Sent', $deliveries[1]->status);
        $this->assertSame('Message for test@example.com accepted by mail.protection.outlook.com (0.0.0.0)', $deliveries[1]->details);
        $this->assertSame('250', $deliveries[1]->output);
        $this->assertFalse($deliveries[1]->sent_with_ssl);
        $this->assertSame('ABCDEF', $deliveries[1]->log_id);
        $this->assertSame(1.15, $deliveries[1]->time);
        $this->assertInstanceOf(DateTime::class, $deliveries[1]->timestamp);
        $this->assertSame('2022-10-18 13:38:17', $deliveries[1]->timestamp->format('Y-m-d H:i:s'));
    }
}

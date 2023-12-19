<?php

declare(strict_types=1);

namespace Postal\Tests;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Postal\ApiException;
use Postal\Client;

class ClientTest extends TestCase
{
    public function testPrepareResponseThrowsInvalidJson(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], 'this is not json');

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Malformed response body received');

        $client->prepareResponse($response, new class() {
        });
    }

    public function testPrepareResponseThrowsJsonNotArray(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], '"test"');

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Malformed response body received');

        $client->prepareResponse($response, new class() {
        });
    }

    public function testPrepareResponseThrowsApiErrors(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], json_encode([
            'status' => 'error',
            'data' => [
                'code' => 'TestExceptionCode',
                'message' => 'my-test-error',
            ],
        ]));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('TestExceptionCode: my-test-error');

        $client->prepareResponse($response, new class() {
        });
    }

    public function testPrepareResponseThrowsWithoutResponse(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], json_encode([
            'status' => 'success',
        ]));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unexpected response received');

        $client->prepareResponse($response, new class() {
        });
    }

    public function testPrepareResponseCoercesToClass(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'id' => 123,
                'string' => 'abc',
            ],
        ]));

        $result = $client->prepareResponse($response, new class([]) {
            public ?int $id;

            public ?string $string;

            public function __construct($attributes)
            {
                $this->id = $attributes['id'] ?? null;
                $this->string = $attributes['string'] ?? null;
            }
        });

        $this->assertSame(123, $result->id);
        $this->assertSame('abc', $result->string);
    }

    public function testPrepareListResponseCoercesToClass(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'id' => 123,
                    'string' => 'abc',
                ],
                [
                    'id' => 456,
                    'string' => 'def',
                ],
            ],
        ]));

        $result = $client->prepareListResponse($response, new class([]) {
            public ?int $id;

            public ?string $string;

            public function __construct($attributes)
            {
                $this->id = $attributes['id'] ?? null;
                $this->string = $attributes['string'] ?? null;
            }
        });

        $this->assertCount(2, $result);
        $this->assertSame(123, $result[0]->id);
        $this->assertSame('abc', $result[0]->string);
        $this->assertSame(456, $result[1]->id);
        $this->assertSame('def', $result[1]->string);
    }

    public function testPrepareListResponseThrowsWithoutList(): void
    {
        $client = new Client('', '');
        $response = new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'string' => 'this is not a list',
            ],
        ]));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unexpected response received, expected a list');

        $client->prepareListResponse($response, new class() {
        });
    }
}

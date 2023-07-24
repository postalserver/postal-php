<?php

declare(strict_types=1);

namespace Postal;

use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Throwable;

class RetryMiddlewareFactory
{
    public static int $defaultRetryDelay = 50;

    public static int $maxRetries = 5;

    public static function build(): callable
    {
        return Middleware::retry(self::buildDecider(), self::buildDelay());
    }

    public static function buildDecider(): callable
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            Throwable $exception = null
        ) {
            if ($retries > self::$maxRetries) {
                return false;
            }

            if (self::isConnectionError($exception)) {
                return true;
            }

            if (self::isInternalServerError($exception) && $request->getMethod() === 'GET') {
                return true;
            }

            return false;
        };
    }

    public static function buildDelay(): callable
    {
        return function (
            $retries,
            Response $response = null
        ) {
            return self::$defaultRetryDelay;
        };
    }

    private static function isConnectionError(\Throwable $exception = null): bool
    {
        return $exception instanceof \GuzzleHttp\Exception\ConnectException;
    }

    private static function isInternalServerError(\Throwable $exception = null): bool
    {
        return $exception instanceof \GuzzleHttp\Exception\ServerException;
    }
}

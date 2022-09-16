<?php

declare(strict_types=1);

namespace Postal\Messages;

use Postal\APIException;

class Message
{
    public int $id;

    public string $token;

    /**
     * @var mixed
     */
    public $status;

    /**
     * @var mixed
     */
    public $details;

    /**
     * @var mixed
     */
    public $inspection;

    /**
     * @var mixed
     */
    public $plain_body;

    /**
     * @var mixed
     */
    public $html_body;

    /**
     * @var mixed
     */
    public $attachments;

    /**
     * @var mixed
     */
    public $headers;

    /**
     * @var mixed
     */
    public $raw_message;

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes)
    {
        if (! is_int($attributes['id'])) {
            throw new APIException('Unexpected API response, expected an integer ID');
        }
        if (! is_string($attributes['token'])) {
            throw new APIException('Unexpected API response, expected a string token');
        }

        $this->id = $attributes['id'];
        $this->token = $attributes['token'];
        $this->status = $attributes['status'] ?? null;
        $this->details = $attributes['details'] ?? null;
        $this->inspection = $attributes['inspection'] ?? null;
        $this->plain_body = $attributes['plain_body'] ?? null;
        $this->html_body = $attributes['html_body'] ?? null;
        $this->attachments = $attributes['attachments'] ?? null;
        $this->headers = $attributes['headers'] ?? null;
        $this->raw_message = $attributes['raw_message'] ?? null;
    }
}

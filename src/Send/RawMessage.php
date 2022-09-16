<?php

declare(strict_types=1);

namespace Postal\Send;

class RawMessage
{
    /**
     * @var array{rcpt_to: array<string>, mail_from: string, data: string}
     */
    public array $attributes;

    public function __construct()
    {
        $this->attributes = [
            'rcpt_to' => [],
            'mail_from' => '',
            'data' => '',
        ];
    }

    public function mailFrom(string $address): self
    {
        $this->attributes['mail_from'] = $address;

        return $this;
    }

    public function rcptTo(string $address): self
    {
        $this->attributes['rcpt_to'][] = $address;

        return $this;
    }

    public function data(string $data): self
    {
        $this->attributes['data'] = base64_encode($data);

        return $this;
    }
}

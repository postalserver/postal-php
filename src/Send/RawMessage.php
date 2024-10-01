<?php

declare(strict_types=1);

namespace Postal\Send;

class RawMessage
{
    /**
     * @var array<string>
     */
    public array $rcpt_to = [];

    public ?string $mail_from = null;

    public ?string $data = null;


    public function mailFrom(string $address): self
    {
        $this->mail_from = $address;

        return $this;
    }

    public function rcptTo(string $address): self
    {
        $this->rcpt_to[] = $address;

        return $this;
    }

    public function data(string $data): self
    {
        $this->data = base64_encode($data);

        return $this;
    }
}

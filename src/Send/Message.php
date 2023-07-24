<?php

declare(strict_types=1);

namespace Postal\Send;

class Message
{
    /**
     * @var array<string>
     */
    public array $to = [];

    /**
     * @var array<string>
     */
    public array $cc = [];

    /**
     * @var array<string>
     */
    public array $bcc = [];

    public ?string $from = null;

    public ?string $sender = null;

    public ?string $subject = null;

    public ?string $tag = null;

    public ?string $reply_to = null;

    public ?string $plain_body = null;

    public ?string $html_body = null;

    /**
     * @var array<string, string>
     */
    public ?array $headers = null;

    /**
     * @var array<array<string, string>>
     */
    public array $attachments = [];

    public function to(string $address): self
    {
        $this->to[] = $address;

        return $this;
    }

    public function cc(string $address): self
    {
        $this->cc[] = $address;

        return $this;
    }

    public function bcc(string $address): self
    {
        $this->bcc[] = $address;

        return $this;
    }

    public function from(string $address): self
    {
        $this->from = $address;

        return $this;
    }

    public function sender(string $address): self
    {
        $this->sender = $address;

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function tag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function replyTo(string $replyTo): self
    {
        $this->reply_to = $replyTo;

        return $this;
    }

    public function plainBody(string $content): self
    {
        $this->plain_body = $content;

        return $this;
    }

    public function htmlBody(string $content): self
    {
        $this->html_body = $content;

        return $this;
    }

    public function header(string $key, string $value): self
    {
        if ($this->headers === null) {
            $this->headers = [];
        }

        $this->headers[$key] = $value;

        return $this;
    }

    public function attach(string $filename, string $content_type, string $data): self
    {
        $attachment = [
            'name' => $filename,
            'content_type' => $content_type,
            'data' => base64_encode($data),
        ];

        $this->attachments[] = $attachment;

        return $this;
    }
}

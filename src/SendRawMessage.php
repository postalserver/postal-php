<?php

namespace Postal;

class SendRawMessage
{
    protected $client;

    public $attributes = [];

    public function __construct($client)
    {
        $this->client = $client;
        $this->attributes['rcpt_to'] = [];
    }

    public function mailFrom($address)
    {
        $this->attributes['mail_from'] = $address;
    }

    public function rcptTo($address)
    {
        $this->attributes['rcpt_to'][] = $address;
    }

    public function data($data)
    {
        $this->attributes['data'] = base64_encode($data);
    }

    public function send()
    {
        $result = $this->client->makeRequest('send', 'raw', $this->attributes);

        return new SendResult($this->client, $result);
    }
}

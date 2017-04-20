<?php
namespace Postal;

class Message
{
    public function __construct($client, $attributes)
    {
        $this->client = $client;
        $this->attributes = $attributes;
    }

    public function id()
    {
        return $this->attributes->id;
    }

    public function token()
    {
        return $this->attributes->token;
    }
}

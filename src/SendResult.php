<?php

namespace Postal;

class SendResult
{
    protected $recipients;

    public function __construct($client, $result)
    {
        $this->client = $client;
        $this->result = $result;
    }

    public function recipients()
    {
        if ($this->recipients != null) {
            return $this->recipients;
        } else {
            $this->recipients = [];

            foreach ($this->result->messages as $key => $value) {
                $this->recipients[strtolower($key)] = new Message($this->client, $value);
            }

            return $this->recipients;
        }
    }

    public function size()
    {
        return count($this->recipients());
    }
}

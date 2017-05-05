<?php
namespace Postal;

class SendMessage
{
    protected $client;

    public $attributes = [];

    public function __construct($client)
    {
        $this->client = $client;
        $this->attributes['to'] = [];
        $this->attributes['cc'] = [];
        $this->attributes['bcc'] = [];
        $this->attributes['headers'] = null;
        $this->attributes['attachments'] = [];
    }

    public function to($address)
    {
        $this->attributes['to'][] = $address;
    }

    public function cc($address)
    {
        $this->attributes['cc'][] = $address;
    }

    public function bcc($address)
    {
        $this->attributes['bcc'][] = $address;
    }

    public function from($address)
    {
        $this->attributes['from'] = $address;
    }

    public function sender($address)
    {
        $this->attributes['sender'] = $address;
    }

    public function subject($subject)
    {
        $this->attributes['subject'] = $subject;
    }

    public function tag($tag)
    {
        $this->attributes['tag'] = $tag;
    }

    public function replyTo($replyTo)
    {
        $this->attributes['reply_to'] = $replyTo;
    }

    public function plainBody($content)
    {
        $this->attributes['plain_body'] = $content;
    }

    public function htmlBody($content)
    {
        $this->attributes['html_body'] = $content;
    }

    public function header($key, $value)
    {
        $this->attributes['headers'][$key] = $value;
    }

    public function attach($filename, $content_type, $data)
    {
        $attachment = [
            'name' => $filename,
            'content_type' => $content_type,
            'data' => base64_encode($data),
        ];

        $this->attributes['attachments'][] = $attachment;
    }


    public function send()
    {
        $result = $this->client->makeRequest('send', 'message', $this->attributes);

        return new SendResult($this->client, $result);
    }
}

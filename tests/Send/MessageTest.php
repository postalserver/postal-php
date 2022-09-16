<?php

declare(strict_types=1);

namespace Postal\Tests\Send;

use PHPUnit\Framework\TestCase;
use Postal\Send\Message;

class MessageTest extends TestCase
{
    public function testSetters(): void
    {
        $message = new Message();
        $this->assertNull($message->headers);

        $message->to('to@example.com');
        $this->assertSame(['to@example.com'], $message->to);

        $message->cc('cc@example.com');
        $this->assertSame(['cc@example.com'], $message->cc);

        $message->bcc('bcc@example.com');
        $this->assertSame(['bcc@example.com'], $message->bcc);

        $message->from('from@example.com');
        $this->assertSame('from@example.com', $message->from);

        $message->sender('sender@example.com');
        $this->assertSame('sender@example.com', $message->sender);

        $message->subject('my-subject');
        $this->assertSame('my-subject', $message->subject);

        $message->tag('my-tag');
        $this->assertSame('my-tag', $message->tag);

        $message->replyTo('reply-to@example.com');
        $this->assertSame('reply-to@example.com', $message->reply_to);

        $message->plainBody('my plain body');
        $this->assertSame('my plain body', $message->plain_body);

        $message->htmlBody('my html body');
        $this->assertSame('my html body', $message->html_body);

        $message->header('my-header', 'value');
        $this->assertSame([
            'my-header' => 'value',
        ], $message->headers);

        $message->attach('test.txt', 'text/plain', 'test');
        $this->assertSame([
            [
                'name' => 'test.txt',
                'content_type' => 'text/plain',
                'data' => 'dGVzdA==',
            ],
        ], $message->attachments);
    }
}

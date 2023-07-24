<?php

declare(strict_types=1);

namespace Postal\Send;

use Postal\Messages\Message;

class Result
{
    public string $message_id;

    /**
     * @var array<Message>
     */
    public array $messages;

    /**
     * @param array{message_id: string, messages: array<string, array<string, string>>} $attributes
     */
    public function __construct(array $attributes)
    {
        $this->message_id = $attributes['message_id'];
        $this->messages = array_change_key_case(
            array_map(fn ($message) => new Message($message), $attributes['messages'])
        );
    }

    /**
     * @return array<Message>
     */
    public function recipients(): array
    {
        return $this->messages;
    }

    public function size(): int
    {
        return count($this->messages);
    }
}

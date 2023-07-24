<?php

declare(strict_types=1);

namespace Postal\Messages;

use DateTime;

class Delivery
{
    public int $id;

    public string $status;

    public string $details;

    public ?string $output;

    public bool $sent_with_ssl;

    public ?string $log_id;

    public ?float $time;

    public DateTime $timestamp;

    /**
     * @param array{id: int, status: string, details: string, output: string|null, sent_with_ssl: bool, log_id: string|null, time: float|null, timestamp: int} $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'];
        $this->status = $attributes['status'];
        $this->details = $attributes['details'];
        $this->output = $attributes['output'];
        $this->sent_with_ssl = $attributes['sent_with_ssl'];
        $this->log_id = $attributes['log_id'];
        $this->time = $attributes['time'];

        $this->timestamp = new DateTime('@' . $attributes['timestamp']);
    }
}

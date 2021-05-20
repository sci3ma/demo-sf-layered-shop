<?php

declare(strict_types=1);

namespace App\Application\Product\Transport;

interface SlackChannelInterface
{
    public function send(string $subject, array $context): void;
}

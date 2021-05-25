<?php

declare(strict_types=1);

namespace App\Application\Product\Transport;

interface ChannelInterface
{
    public function send(string $subject, array $context, ?string $template = null): void;
}

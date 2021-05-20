<?php

declare(strict_types=1);

namespace App\Application\Product\Transport;

interface EmailChannelInterface
{
    public function send(string $subject, array $context, string $template): void;
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Transport;

use App\Application\Product\Transport\ChannelInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

final class EmailChannel implements ChannelInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $subject, array $context, ?string $template = null): void
    {
        $email = (new TemplatedEmail())
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            // Handle error, resend email, log error, etc.
        }
    }
}

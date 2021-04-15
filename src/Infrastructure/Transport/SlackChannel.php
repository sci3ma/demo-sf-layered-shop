<?php

declare(strict_types=1);

namespace App\Infrastructure\Transport;

use App\Application\Product\Model\ProductViewModel;
use InvalidArgumentException;
use Symfony\Component\Notifier\Bridge\Slack\Block\SlackActionsBlock;
use Symfony\Component\Notifier\Bridge\Slack\SlackOptions;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

final class SlackChannel implements ChannelInterface
{
    private ChatterInterface $chatter;

    public function __construct(ChatterInterface $chatter)
    {
        $this->chatter = $chatter;
    }

    public function send(string $subject, array $context, ?string $template = null): void
    {
        if (!isset($context['product']) || !$context['product'] instanceof ProductViewModel) {
            throw new InvalidArgumentException('Context array is not valid. Should contains Product.');
        }

        $product = $context['product'];

        $message = (new ChatMessage($subject))
            ->transport('slack')
            ->options($this->prepareSlackOptions($product));

        try {
            $this->chatter->send($message);
        } catch (TransportExceptionInterface $exception) {
            // Handle error, resend message, log error, etc.
        }
    }

    private function prepareSlackOptions(ProductViewModel $product): SlackOptions
    {
        $button = (new SlackActionsBlock())
            ->button(
                $product->getTitle(),
                sprintf('https://127.0.0.1:8000/product/view/%s', $product->getId()),
                'primary'
            );

        return (new SlackOptions())
            ->block($button);
    }
}

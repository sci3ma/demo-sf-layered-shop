<?php

declare(strict_types=1);

namespace App\Application\Product\Event;

use App\Application\Product\Model\ProductViewModel;
use App\Domain\Entity\Product\Event\ProductAddedEvent;
use App\Infrastructure\Transport\EmailChannel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ProductEmailNotifierSubscriber implements EventSubscriberInterface
{
    private EmailChannel $channel;

    public function __construct(EmailChannel $channel)
    {
        $this->channel = $channel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductAddedEvent::NAME => 'onProductAdded',
        ];
    }

    public function onProductAdded(ProductAddedEvent $event): void
    {
        $this->channel->send(
            'New product added.',
            ['product' => ProductViewModel::fromEntity($event->getProduct())],
            '@App/Product/email/added.html.twig'
        );
    }
}

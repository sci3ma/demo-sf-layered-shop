<?php

declare(strict_types=1);

namespace App\Application\Product\Event;

use App\Application\Product\Model\ProductViewModel;
use App\Application\Product\Transport\EmailChannelInterface;
use App\Domain\Entity\Product\Event\ProductAddedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ProductEmailNotifierSubscriber implements EventSubscriberInterface
{
    private EmailChannelInterface $channel;

    public function __construct(EmailChannelInterface $channel)
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

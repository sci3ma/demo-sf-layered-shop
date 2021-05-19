<?php

declare(strict_types=1);

namespace App\Infrastructure\Product\EventListener;

use App\Domain\Entity\Product\Event\ProductAddedEvent;
use App\Domain\Entity\Product\Product;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ProductAddedNotifier
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function postPersist(Product $product): void
    {
        $this->dispatcher->dispatch(
            new ProductAddedEvent($product),
            ProductAddedEvent::NAME
        );
    }
}

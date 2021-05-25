<?php

declare(strict_types=1);

namespace App\Infrastructure\Product\EventListener;

use App\Application\Product\Model\ProductViewModel;
use App\Application\Product\Transport\ChannelInterface;
use App\Domain\Entity\Product\Product;

final class ProductAddedNotifier
{
    /**
     * @var ChannelInterface[]|iterable
     */
    private iterable $productNotificationStrategies;

    public function __construct(iterable $productNotificationStrategies)
    {
        $this->productNotificationStrategies = $productNotificationStrategies;
    }

    public function postPersist(Product $product): void
    {
        foreach ($this->productNotificationStrategies as $productNotificationStrategy) {
            $productNotificationStrategy->send(
                'New product added.',
                ['product' => ProductViewModel::fromEntity($product)],
                '@App/Product/email/added.html.twig'
            );
        }
    }
}

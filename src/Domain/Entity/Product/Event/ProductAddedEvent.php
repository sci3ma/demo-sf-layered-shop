<?php

declare(strict_types=1);

namespace App\Domain\Entity\Product\Event;

use App\Domain\Entity\Product\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductAddedEvent extends Event
{
    public const NAME = 'product.added';

    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}

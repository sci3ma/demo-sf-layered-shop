<?php

declare(strict_types=1);

namespace App\Domain\Repository\Product;

use App\Domain\Entity\Product\Product;

interface SaveProductQueryInterface
{
    public function execute(Product $product): void;
}

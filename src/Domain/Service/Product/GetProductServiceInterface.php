<?php

declare(strict_types=1);

namespace App\Domain\Service\Product;

use App\Application\Product\Model\ProductViewModel;

interface GetProductServiceInterface
{
    public function execute(string $productId): ProductViewModel;
}

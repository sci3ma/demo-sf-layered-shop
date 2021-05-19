<?php

declare(strict_types=1);

namespace App\Application\Product\Service;

use App\Application\Product\Model\ProductViewModel;

interface GetProductServiceInterface
{
    public function execute(string $productId): ProductViewModel;
}

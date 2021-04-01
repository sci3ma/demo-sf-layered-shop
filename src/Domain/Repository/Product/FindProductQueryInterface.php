<?php

declare(strict_types=1);

namespace App\Domain\Repository\Product;

use App\Domain\Entity\Product\Product;
use Symfony\Component\Uid\AbstractUid;

interface FindProductQueryInterface
{
    public function execute(): ?Product;

    public function byId(AbstractUid $productId): self;
}

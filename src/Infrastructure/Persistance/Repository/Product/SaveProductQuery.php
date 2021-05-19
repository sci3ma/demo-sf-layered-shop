<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Repository\Product;

use App\Domain\Entity\Product\Product;
use App\Domain\Repository\Product\SaveProductQueryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class SaveProductQuery implements SaveProductQueryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}

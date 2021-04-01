<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Repository\Product;

use App\Domain\Entity\Product\Product;
use App\Domain\Repository\Product\GetAllProductsQueryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class GetAllProductsQuery implements GetAllProductsQueryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): Query
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('product')
            ->from(Product::class, 'product')
            ->orderBy('product.createdAt', Criteria::DESC)
            ->getQuery();
    }
}

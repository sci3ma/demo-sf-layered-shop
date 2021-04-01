<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Repository\Product;

use App\Domain\Entity\Product\Product;
use App\Domain\Repository\Product\FindProductQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Uid\AbstractUid;

class FindProductQuery implements FindProductQueryInterface
{
    private ?AbstractUid $id = null;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): ?Product
    {
        if (null === $this->id) {
            throw new InvalidArgumentException('Product ID is missing.');
        }

        return $this->entityManager->find(Product::class, $this->id);
    }

    public function byId(AbstractUid $productId): self
    {
        $this->id = $productId;

        return $this;
    }
}

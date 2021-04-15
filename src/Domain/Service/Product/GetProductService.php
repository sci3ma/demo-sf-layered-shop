<?php

declare(strict_types=1);

namespace App\Domain\Service\Product;

use App\Application\Product\Model\ProductViewModel;
use App\Domain\Repository\Product\FindProductQueryInterface;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Ulid;

final class GetProductService implements GetProductServiceInterface
{
    private FindProductQueryInterface $findProductQuery;

    public function __construct(FindProductQueryInterface $findProductQuery)
    {
        $this->findProductQuery = $findProductQuery;
    }

    public function execute(string $productId): ProductViewModel
    {
        try {
            $productUild = Ulid::fromString($productId);
        } catch (InvalidArgumentException $exception) {
            throw new NotFoundHttpException('Product not found', $exception);
        }

        if (null === $product = $this->findProductQuery->byId($productUild)->execute()) {
            throw new NotFoundHttpException();
        }

        return ProductViewModel::fromEntity($product);
    }
}

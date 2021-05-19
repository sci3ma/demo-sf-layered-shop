<?php

declare(strict_types=1);

namespace App\Application\Product\Factory;

use App\Application\Product\Form\Model\ProductCreateFormModel;
use App\Domain\Entity\Product\Product;

final class ProductFactory
{
    public static function createFromFormModel(ProductCreateFormModel $productCreateFormModel): Product
    {
        return new Product(
            $productCreateFormModel->title,
            $productCreateFormModel->description,
            $productCreateFormModel->price
        );
    }
}

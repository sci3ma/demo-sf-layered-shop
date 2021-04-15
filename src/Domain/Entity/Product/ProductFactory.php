<?php

declare(strict_types=1);

namespace App\Domain\Entity\Product;

use App\Application\Product\Form\Model\ProductCreateFormModel;

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

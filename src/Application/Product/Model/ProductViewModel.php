<?php

declare(strict_types=1);

namespace App\Application\Product\Model;

use App\Domain\Entity\Product\Product;
use Symfony\Component\Uid\Ulid;

final class ProductViewModel
{
    private ?Ulid $id = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?string $price = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public static function fromEntity(Product $product): self
    {
        $productViewModel = new self();
        $productViewModel->id = $product->getId();
        $productViewModel->description = $product->getDescription();
        $productViewModel->price = $product->getPrice();
        $productViewModel->title = $product->getTitle();

        return $productViewModel;
    }
}

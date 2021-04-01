<?php

declare(strict_types=1);

namespace App\Domain\Entity\Product;

use App\Application\Product\Form\Model\ProductCreateFormModel;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
final class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private ?Ulid $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    public function __construct(
        string $title,
        string $description,
        string $price
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
    }

    public static function createFromFormModel(ProductCreateFormModel $productCreateFormModel): self
    {
        return new self(
            $productCreateFormModel->title,
            $productCreateFormModel->description,
            $productCreateFormModel->price
        );
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }
}

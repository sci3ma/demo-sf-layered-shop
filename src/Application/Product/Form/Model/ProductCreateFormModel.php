<?php

declare(strict_types=1);

namespace App\Application\Product\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class ProductCreateFormModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    public ?string $title = null;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=1)
     */
    public ?string $description = null;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public ?string $price = null;
}

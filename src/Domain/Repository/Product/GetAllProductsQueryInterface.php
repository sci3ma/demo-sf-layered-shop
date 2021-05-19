<?php

declare(strict_types=1);

namespace App\Domain\Repository\Product;

use Doctrine\ORM\Query;

interface GetAllProductsQueryInterface
{
    public function execute(): Query;
}

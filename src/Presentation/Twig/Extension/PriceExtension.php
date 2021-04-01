<?php

declare(strict_types=1);

namespace App\Presentation\Twig\Extension;

use Symfony\Component\Intl\Currencies;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{
    private string $currencyType;

    public function __construct(string $currencyType)
    {
        $this->currencyType = $currencyType;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice(string $number): string
    {
        return sprintf(
            '%s %01.2f',
            Currencies::getSymbol($this->currencyType),
            $number / 100
        );
    }
}

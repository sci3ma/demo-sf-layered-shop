<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Product;

use App\Domain\Service\Product\GetProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/view/{productId}", name="product_view")
 */
final class ViewController extends AbstractController
{
    public function __invoke(GetProductServiceInterface $getProductService, string $productId): Response
    {
        return $this->render('@App/Product/view.html.twig', [
            'productView' => $getProductService->execute($productId),
        ]);
    }
}

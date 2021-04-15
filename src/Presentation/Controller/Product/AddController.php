<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Product;

use App\Application\Product\Form\Model\ProductCreateFormModel;
use App\Application\Product\Form\Type\ProductCreateType;
use App\Domain\Entity\Product\ProductFactory;
use App\Domain\Repository\Product\SaveProductQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/add", name="product_add")
 */
final class AddController extends AbstractController
{
    public function __invoke(
        Request $request,
        SaveProductQueryInterface $saveProductQuery,
        string $currencyType
    ): Response {
        $form = $this->createForm(
            ProductCreateType::class,
            new ProductCreateFormModel(),
            ['currency' => $currencyType]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = ProductFactory::createFromFormModel($form->getData());
            $saveProductQuery->execute($product);

            return $this->redirectToRoute('product_view', [
                'productId' => $product->getId(),
            ]);
        }

        return $this->render('@App/Product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

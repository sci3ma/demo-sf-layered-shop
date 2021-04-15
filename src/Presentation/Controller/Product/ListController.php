<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Product;

use App\Domain\Repository\Product\GetAllProductsQueryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/list", name="product_list")
 */
final class ListController extends AbstractController
{
    public function __invoke(
        PaginatorInterface $paginator,
        Request $request,
        GetAllProductsQueryInterface $getAllProductsQuery,
        int $itemsPerPage
    ): Response {
        $pagination = $paginator
            ->paginate(
                $getAllProductsQuery->execute(),
                $request->query->getInt('page', 1),
                $itemsPerPage
            );

        return $this->render('@App/Product/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}

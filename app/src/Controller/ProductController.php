<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(
            ProductRepository $productRepository
    )
    : Response {

        return $this->render(
                        'product/index.html.twig',
                        [
                            'products' => $productRepository->findBy(array(), array('id' => 'DESC'), 10, 0),
                        ]
        );
    }

}

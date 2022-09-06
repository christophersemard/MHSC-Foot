<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boutique')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'app_shop/home')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_shop/product')]
    public function product($slug, ProductRepository $productRepository): Response
    {
        $product =  $productRepository->findBySlug($slug)[0];;

        return $this->render('shop/product.html.twig', [
            'controller_name' => 'ShopController',
            'product' => $product,
        ]);
    }
}

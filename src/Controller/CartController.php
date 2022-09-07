<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{



    #[Route('/boutique/mon-panier', name: 'app_cart')]
    public function cart(CartService $cartService): Response
    {
        return $this->render('shop/cart.html.twig', [
            'controller_name' => 'ShopController',
            'items' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart/add')]
    public function add($id, CartService $cartService): Response
    {
        $cartService->add($id);
        return $this->redirectToRoute(("app_cart"));
    }

    #[Route('/remove/{id}', name: 'app_cart/remove')]
    public function remove($id, CartService $cartService): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute(("app_cart"));
    }
}

<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    protected $requestStack;
    protected $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }


    public function add(int $id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
    }


    public function remove(int $id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
    }

    public function getCart()
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {

            $cartWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $cartWithData;
    }

    public function getProducts()
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        $listProducts = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $listProducts[] = [
                'name' => $product->getName(),
                'quantity' => $quantity,
                'price' => $product->getPrice(),
            ];
        }

        return $listProducts;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    public function deleteCart()
    {
        $session = $this->requestStack->getSession();
        $session->remove('cart');
    }

    public function updateProduct($product)
    {
        $id = $product['product'];
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $cart[$id] = $product['quantity'];
        $session->set('cart', $cart);
    }
}

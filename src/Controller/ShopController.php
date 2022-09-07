<?php

namespace App\Controller;

use Stripe;
use App\Entity\Order;
use App\Entity\Address;
use App\Service\CartService;
use App\Form\RegistrationAddressType;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/commande', name: 'app_shop/order')]
    public function order(CartService $cartService, Request $request, AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();
        $address = $user->getAddress();
        $form = $this->createForm(RegistrationAddressType::class, $address);
        $form->handleRequest($request);

        $cart = $cartService->getCart();

        if ($form->isSubmitted() && $form->isValid()) {

            $addressRepository->add($address, true);
            return $this->redirectToRoute('app_shop/order', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop/order.html.twig', [
            'controller_name' => 'ShopController',
            'form' => $form->createView(),
            'user' => $user,
            'cart' => $cart,
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/checkout', name: 'app_shop/checkout')]
    public function checkout(CartService $cartService, SerializerInterface $serializer, OrderRepository $orderRepository)
    {
        $order = new Order;

        $user = $this->getUser();
        $order->setUser($user);

        $order->setAddress($serializer->normalize($user->getAddress(), null));

        $cart = [];
        foreach ($cartService->getCart() as $item) {
            $cart[] = [
                'product' => $serializer->normalize($item['product'], null),
                'quantity' => $item['quantity']
            ];
        }
        $order->setCart($cart);
        $order->setAmount($cartService->getTotal());


        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY_TEST']);
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [
                array_map(fn (array $product) => [
                    'quantity' => $product['quantity'],
                    "price_data" => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $product['name']
                        ],
                        'unit_amount' => $product['price'] * 100,
                    ]
                ], $cartService->getProducts())
            ],
            'mode' => 'payment',
            'success_url' => $_ENV['MY_DOMAIN'] . 'boutique/confirmation-commande/' . $order->getReference(),
            'cancel_url' => $_ENV['MY_DOMAIN'] . 'boutique/mon-panier',
            'metadata'                    => [
                'order_id' => $order->getReference()
            ]
        ]);


        $order->setStripeSessionId($checkout_session->id);

        $orderRepository->add($order, true);

        return $this->redirect($checkout_session->url);
    }


    #[Route('/confirmation-commande/{reference}', name: 'app_shop/order_confirm')]
    public function orderConfirm($reference, CartService $cartService, OrderRepository $orderRepository): Response
    {

        $order = $orderRepository->findOneBy(array('reference' => $reference));

        $cartService->deleteCart();

        return $this->render('shop/order_confirm.html.twig', [
            'controller_name' => 'ShopController',
            'order' => $order,
            'total' => $cartService->getTotal(),
        ]);
    }
}

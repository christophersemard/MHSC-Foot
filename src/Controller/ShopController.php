<?php

namespace App\Controller;

use Stripe;
use App\Entity\Order;
use App\Entity\Address;
use App\Service\CartService;
use App\Form\SearchProductType;
use App\Repository\OrderRepository;
use App\Form\RegistrationAddressType;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/boutique')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'app_shop/home')]
    public function index(ProductRepository $productRepository, ProductCategoryRepository $productCategoriesRepository, Request $request): Response
    {
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);

        $products = $productRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $selectedCategories = [];
            if (count($data['category']) > 0) {
                foreach ($form->getData('category')['category'] as $category) {
                    $selectedCategories[] = $category;
                }
            }

            $minPrice = $data['minPrice'] ? $data['minPrice'] : 0;
            $maxPrice = $data['maxPrice'] ? $data['maxPrice'] : 1000000;


            $selectedSizes = [];
            if (count($data['sizes']) > 0) {
                foreach ($form->getData('sizes')['sizes'] as $size) {
                    $selectedSizes[] = $size;
                }
            }

            $products = $productRepository->findBySearchCriteria($selectedCategories, $minPrice, $maxPrice, $selectedSizes);

            // dd($products[0]->getSizes());
        }

        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'form' => $form->createView(),
            'products' => $products,
            'categories' => $productCategoriesRepository->findAll(),
        ]);
    }

    // #[Route('/categorie/{slug}', name: 'app_shop/category')]
    // public function category($slug, ProductCategoryRepository $productCategoryRepository): Response
    // {
    //     $product =  $productCategoryRepository->findBySlug($slug)[0];;

    //     return $this->render('shop/product.html.twig', [
    //         'controller_name' => 'ShopController',
    //         'product' => $product,
    //     ]);
    // }

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
                'product' => $serializer->normalize(
                    $item['product'],
                    null,
                    [
                        'groups' => ['cart_product']
                    ]
                ),
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

<?php

namespace App\Controller;

use Stripe;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/webhook')]
class WebhookController extends AbstractController
{
    #[Route('/', name: 'app_stripe/webhook')]
    public function index(Request $request, OrderRepository $orderRepository)
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY_TEST']);
        $endpoint_secret = "whsec_bd3e93213d384f0dd8e50c27c1490306f8a2af25e2075089da404568bb411cc3";

        function print_log($val)
        {
            return file_put_contents('php://stderr', print_r($val, TRUE));
        }


        $payload = @file_get_contents('php://input');
        $header = 'Stripe-Signature';
        $sig_header = $request->headers->get($header);
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new JsonResponse([['status' => 400]]);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new JsonResponse([['status' => 400]]);
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $order = $orderRepository->findOneBy(array('stripeSessionId' => $session->id));
            $order->setStatus('paied');
            $orderRepository->add($order, true);
        }

        return new JsonResponse([['session' => $session, 'status' => 200]]);
    }
}

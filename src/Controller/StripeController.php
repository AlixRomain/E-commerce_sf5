<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session/{reference}", name="stripe",methods="POST")
     */
    public function index(EntityManagerInterface $entityManager,$reference ): Response
    {
        // Enregistrement du produit pour stripe:
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';
        $product_for_stripe=[];
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        //Ce que l'on retourne si l'on ne trouve pas d'order en base (voir script JS coté twig)
        if(!$order){
            return new JsonResponse(['error'=> 'recup_order_failed']);
        }
        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getImage()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }//End Foreach
        //Ajout de la livraison
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51HyDkYCd4Oy8omI2Rwkw9yUTj3tRXzGGxwCjtqk6nDRKZ8EVw8cral4c3k6CtmZ4Q1emspppdQW90nCbfwfCsCVT00ECaz7EFN');
        //Si on fait un dump le prix sera vu X100 , Stripe le redivise /100 de son côté

        $checkout_session = Session::create([
            'customer_email'=> $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [$product_for_stripe],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);
        //Enregegistrement en Table ORDER de la session ID de Stripe
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        //Retour de l'id de la session encodé en Json à la fonction JS
        $response = new JsonResponse(['id'=> $checkout_session->id]);
        return $response;


    }
}

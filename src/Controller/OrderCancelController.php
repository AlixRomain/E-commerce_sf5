<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    //Ce controller est chargé de retourner la vue en cas d'ERREUR d'une transaction Stripe
    /**
     * @Route("/commande/erreur/{sessionStripeId}", name="order_cancel")
     */
    public function index($sessionStripeId): Response
    {

        $order= $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($sessionStripeId);

        if(!$order || $order->getUser() != $this->getUser() ){
            return $this->redirectToRoute('home');
        }

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order,
        ]);
    }
}

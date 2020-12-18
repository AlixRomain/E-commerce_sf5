<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    private $entityManager;

    function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    //Ce controller est chargé de retourner la vue en cas de SUCCES d'une transaction Stripe
    /**
     * @Route("/commande/merci/{sessionStripeId}", name="order_validate")
     */
    public function index($sessionStripeId, Cart $cart): Response
    {
        $order= $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($sessionStripeId);

        if(!$order || $order->getUser() != $this->getUser() ){
            return $this->redirectToRoute('home');
        }
        //Si c'est bien la première fois qu'il arrive sur cette page après avoir payé avoir succès
        if($order->getIsPaid() != 1){
            //Je modifie l'état de statu du paiement
            $order->setIsPaid(1);
            $this->entityManager->flush();
            //Je supprile ma session Panier
            $cart->remove();
            //j'envoie un mail de confirmation au client


        }
        return $this->render('order_validate/index.html.twig', [
            'order' => $order,
        ]);
    }
}

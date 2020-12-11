<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        //Ici via la methodes getValues() je regarde si la relation adress/User à bien une adresse enregistré.
        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('account_adress-add');
        }
        //ici je passe en troisieme param les datas de l'utilisateurs en cours pour que dans le formulaire il ne m'affiche que
        //les adresse liées à cet utilisateur
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);
        // Le traitement du formulaire ce fait dans la vue suivante name="order_recap" ci-dessous

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull($cart)
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods="POST")
     */
    public function add(Cart $cart, Request $request): Response
    {


        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $date = new \DateTime();
            $carriers= $form->get("carriers")->getData();
            $delivery= $form->get("addresses")->getData();

            $delivery_content = $delivery->getName().''.$delivery->getLastName();
            $delivery_content .= $delivery->getPhone();
           // if($delivery->getCompany()){
              //  $delivery_content .= $delivery->getCompany();
              //  $delivery_content .= $delivery->getCountry();
          //  }
            $delivery_content .= $delivery->getAdress();
            $delivery_content .= $delivery->getCity().''.$delivery->getPostal();

            //Hydratation de l'objet Order
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            //Hydratation de l'objet OrderDetail
            foreach ($cart->getFull($cart) as $product){
                $orderD = new OrderDetails();
                $orderD->setMyOrder( $order);
                $orderD->setProduct($product['product']->getName());
                $orderD->setQuantity($product['quantity']);
                $orderD->setPrice($product['product']->getPrice());
                $orderD->setTotal( $product['quantity'] * $product['product']->getPrice());
                $this->entityManager->persist($orderD);
            }
            $this->entityManager->flush();

            // Nous retournons la vue que si le formulaire à été soumis dans l'url /commande.
            return $this->render('order/recap.html.twig', [
                'cart' => $cart->getFull($cart),
                'carrier'=> $carriers,
                'address' =>  $delivery,
            ]);
        }

        //ici on redirige vers  si l'utilisateur est arrivé ici via l'url et non la soumission du formulaire dans l'url /commande
        return $this->redirectToRoute('my-cart');

    }
}

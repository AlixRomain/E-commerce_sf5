<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
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
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){
            dd($form->getData());
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull($cart)
        ]);
    }
}

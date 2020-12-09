<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    private $entityManager;

    function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/adresses", name="account_adress")
     */
    public function index(): Response
    {


        return $this->render('account/adress.html.twig', [
        ]);
    }
    /**
     * @Route("/compte/ajouter-une-adresse", name="account_adress-add")
     */
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();
        $form   = $this->createForm(AdressType::class, $address);


        $form->handleRequest($request);
        // Si le formulaire est soumis
        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            //si j'ai un truc dans le panier est que donc je suis dans le tunnel d'achat renvois moi la d'ou je viens
            if($cart->get()){
                return  $this->redirectToRoute('order');
            }else{
                return  $this->redirectToRoute('account_adress');
            }
        }
        return $this->render('account/address-form.html.twig', [
            'form'            => $form->createView(),
        ]);
    }
    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_adress-edit")
     */
    public function edit(Request $request, $id): Response
    {
        $address = new Address();
        $address =  $this->entityManager->getRepository(Address::class)->findOneById($id);
        // Si j'ai pas trouver l'adresse en base ou si elle appartient pas à l(utilisateur
        if(!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_adress');
        }else{
            $form   = $this->createForm(AdressType::class, $address);

        }

        $form->handleRequest($request);
        // Si le formulaire est soumis
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return  $this->redirectToRoute('account_adress');
        }
        return $this->render('account/address-form.html.twig', [
            'form'            => $form->createView(),
        ]);
    }
    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_adress-delete")
     */
    public function delete($id): Response
    {
        $address =  $this->entityManager->getRepository(Address::class)->findOneById($id);
        // Si j'ai pas trouver l'adresse en base ou si elle appartient pas à l(utilisateur
        if($address || $address->getUser() == $this->getUser()){
            $this->entityManager->remove($address);
            $this->entityManager->flush();

        }
            return $this->redirectToRoute('account_adress');
    }
}

<?php

namespace App\Controller;

use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{


    /**
     * @Route("/mon-panier", name="my-cart")
     */
    public function index(Cart $cart): Response
    {
            $cartComplete = $cart->getFull($cart);
            return $this->render('cart/index.html.twig', [
                'cart' => $cartComplete,
            ]);

    }

    // une fois produit créer on redirige vers la vue du panier
    /**
     * @Route("/cart/add/{id}", name="add-to-cart")
     */
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('my-cart');
    }

    // une fois retirer on redirige vers la vue du panier
    /**
     * @Route("/cart/min/{id}", name="min-to-cart")
     */
    public function min(Cart $cart, $id): Response
    {
        $cart->min($id);
        return $this->redirectToRoute('my-cart');
    }



    // une fois le panier supprimén redirige vers la vue les produits
    /**
     * @Route("/cart/remove", name="remove-my-cart")
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }

    // Suppression d'un produit dans le panier
    /**
     * @Route("/cart/delete/{id}", name="delete-one-product")
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('my-cart');
    }
}

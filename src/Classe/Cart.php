<?php
namespace App\Classe;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class Cart
{
    private $entityManager;
    private $session;

    function __construct( EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function add($id)
    {
    if(!is_null($this->entityManager->getRepository(Product::class)->findOneById($id))){
        $cart = $this->session->get('cart',[]);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $this->session->set('cart',$cart);
    }

    }
    public function min($id)
    {
        if(!is_null($this->entityManager->getRepository(Product::class)->findOneById($id))) {
            $cart = $this->session->get('cart', []);
            if (isset($cart[$id]) && $cart[$id] > 1) {
                $cart[$id]--;
            } else if ($cart[$id] == 1) {
                unset($cart[$id]);
            }
            return $cart = $this->session->set('cart', $cart);
        }
    }


    public function delete($id){
        if(!is_null($this->entityManager->getRepository(Product::class)->findOneById($id))) {
            $cart = $this->session->get('cart', []);
            if (!isset($cart[$id])) {
                return $cart;
            } else {
                unset($cart[$id]);
            }
            return $cart = $this->session->set('cart', $cart);
        }
    }

    public function remove(){
       return  $this->session->remove('cart');
    }

    public function get(){
        return $this->session->get('cart');
    }

    public  function getFull($cart){
        $cartComplete = [];
        if($cart->get()) {
            foreach ($cart->get() as $id => $quantity) {
                $cartComplete[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}
<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        return $this->render('home_page/index.html.twig', [
            'products'=>$product,
            'headers'=>$headers,
        ]);
    }

}

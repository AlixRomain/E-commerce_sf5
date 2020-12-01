<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    #ici nous passons le manager de doctrine à la var $entitymanager
    function __construct( EntityManagerInterface $entityManager)
    {
        #puis nous la passons à notre Variable Private déclarer en L 19. Elle remplacera $doctrine dans le traitement
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="register")
     */

    #injection de dépendance avec l'objet Request. Je ne rentre pas dans cette fonction sans ces objet instancié
    #UserPasswordEncoderInterface sert à appeler la méthode d'encodage des mdp de symfony
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        ####//////////// Au CHARGEMENT DE LA PAGE//////////////
        #instantiation de la classe user
        $user = new User();
        #instanciation du formulaire avec 2 params: la class/ l'objet
        $form = $this->createForm(RegisterType::class, $user);
        ###-------------------------Fin chargement de la page

        ####//////////// A la SOUMISSION DU FORMULAIRE/////////////
        #La methode handleRequest veille, il vérifie si le formulaire à été soumis/validé ou non
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            #tu m'hydrate l'objet user à partir des datas du formulaire et tu me crypte le Password
                $user     = $form->getData();
                $passwordEncoder = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($passwordEncoder);
            #tu m'appel Doctrine et tu lui dit de ramener son manager
                # $doctrine = $this->getDoctrine()->getManager();
            #le manager de doctrine va figer les datas de l'objet $user dans un historique des modifications
               # $doctrine->persist($user);
                 $this->entityManager->persist($user);
            # Maintenant qu'elle est figée tu me l'enregistre, tu execute l'historique des modifications
              # $doctrine->flush();
                 $this->entityManager->flush();

        }
        ###-------------------------Fin du traitement apres soumissions du formulaire



        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

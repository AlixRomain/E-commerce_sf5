<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $notification = null;
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
            #tu m'hydrate l'objet user à partir des datas du formulaire
                $user     = $form->getData();
                #Tu m'assure que la personne n'est pas déjà inscrite en base
                $search_email = $this->entityManager->getRepository((User::class))->findOneByEmail($user->getEmail());
                #Si j'ai pas d'email à ce nom
                if(!$search_email){
                    #Tu me crypte le Password avant de l'insérer en table
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

                    #traitement du mail D'inscription
                    $mail = new Mail();
                    $content = 'Bonjour'.$user->getFirstName().'<br>Bienvenue sur la première boutique dédiée au made in France. <br><br>';
                    $mail->send($user->getEmail(), $user->getFirstName(),'Bienvenue sur Mon site|E-commerce',$content);
                    $notification = 'Votre inscription s\'est déroulée avec succès. Un email vous à été envoyé à l\'adresse '.$user->getEmail();

                    $this->redirectToRoute('products');
                }else{
                    $notification = "L'email renseignée existe déjà en base." ;
                }



        }
        ###-------------------------Fin du traitement apres soumissions du formulaire



        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification'=> $notification
        ]);
    }
}

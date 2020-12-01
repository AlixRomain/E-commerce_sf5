<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/modification-password", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user );
        #La methode handleRequest veille, il vérifie si le formulaire à été soumis/validé ou non
        $form->handleRequest($request);

        $notification = null;

        if($form->isSubmitted() && $form->isValid()){

            #je recupere l'ancsien password
            $old_password = $form->get('old_password')->getData();



            #Ici on AUTHENTIFIE l'UTILISATEUR AVANT LA MODIFICATION
            #la méthode va encoder le old_password et revoyer true si ça match avec celui en base.
            if($encoder->isPasswordValid($user, $old_password)){
                $new_password = $form->get('new_password')->getData();
                $passwordEncoder = $encoder->encodePassword($user, $new_password);
                $user->setPassword($passwordEncoder);
                #tu m'appel Doctrine et tu lui dit de ramener son manager
                $doctrine = $this->getDoctrine()->getManager();
                #PERSIST inutuile pour un UPDATE
                # Maintenant qu'elle est figée tu me l'enregistre, tu execute l'historique des modifications
                $doctrine->flush();

                $notification = "Votre changement de mot de passe à bien été pri en compte.";

            }else{
                $notification = "Le mot de passe insérer ne correspond pas avec celui inséré en base.";
            }
        }


        return $this->render('account/account_password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}

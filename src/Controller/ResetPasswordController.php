<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    //Index nous permet d'allez cherchez la vue d'enregistrer la demande de changement de mots de passe de l'utilisateur
    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        //Si mon utilisateur est déjà logger je le reroute vers son compte
        if($this->getUser()){
           return $this->redirectToRoute('home');
        }
        //Si on rentre un email je m'assure qu'il exite bien en BDD sinon je reroute vers la page de connexion
        if($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user){
                //1 enregistrer en base la demande de reset PassWord
                $resetPass = new ResetPassword();
                $resetPass->setUser($user);
                $resetPass->setToken(uniqid());
                $resetPass->setCreatedAt(new \DateTime());

                $this->entityManager->persist($resetPass);
                $this->entityManager->flush();

                //2 envoyer un email avec un lien pour MAJ le mot de passe
                $url = $this->generateUrl('update_password',[
                    'token' =>$resetPass->getToken(),
                ]);
                $mail = new Mail();
                $content="Bonjour".$user->getFirstName()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site Monsite|E-commerce.<br/><br/>";
                $content.= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe</a>";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Réinitialier votre mot de passe sur mon site E-commerce',$content);

                $this->addFlash('notice', 'Vous allez recevoir dans queleques secondes un mail pour réinitialiser votre mot de passe.');

            }else{
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
                return $this->redirectToRoute('reset_password');
            }
        }
        return $this->render('reset_password/index.html.twig', [
        ]);
    }
    //Ici on s'assure que le token envoyé === reçu  et on fait un update crypté du nouveau Mdp en BDD avant de rediriger vers la connexion
    /**
     * @Route("/modifier-mon-mot-de-passe-oublie/{token}", name="update_password")
     */
    public function update($token, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $reset_pass = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if(!$reset_pass){
            return $this->redirectToRoute('reset_password');
        }
        //Vérifier si me created AT = maintenant -3h. On utilise la methode modify rattaché à un datetime pour incrémenter de 3h le createdAt
        $now = new \DateTime();

        if($now > $reset_pass->getCreatedAt()->modify('+ 3 hour')){
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de le renouveller à nouveau.');
            return $this->redirectToRoute('reset_password');
        }
        //Rendre une vue avec mot de passe et confirmer votre mot de passe
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $newMdp = $form->get('new_password')->getData();
            //Encodage des mots de passe
            $user= $reset_pass->getUser();
            $mdpEncoder = $encoder->encodePassword($user, $newMdp);
            $user->setPassword($mdpEncoder);
            //flush en Bdd
            $this->entityManager->flush();
            //Redirection de l'utilisateur ders la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');

        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

Class EasyAdminProductImage implements EventSubscriberInterface {
//////////////////////////////////////////////////////////////////////////////////////
/// /////////////////////////////////////////////////////////////////////////////////
/// //////////////FICHIER INUTILE EST CADUC APRES MISE A JOUR EASY BUNDLE///////////
/// MAIS TRES UTILE POUR S'INSTRUIRE SUR SYMFONY///////////////////////////////////
/// NOTAMENT POUR PLACER DES ACTIONS AVANT UN EVENEMENT PARTICULIER COMME PERSIST
    private $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        return $this->appKernel = $appKernel;
    }

    #ici on dit à qu'elle moment tu écoute et tu joue la fonction que je te donne
    public static function getSubscribedEvents()
    {
        #Je veux que juste avant que tu persist l'attribut image de l'entité produit
       return [
            BeforeEntityPersistedEvent::class => ['setImage'],
            BeforeEntityUpdatedEvent::class=> ['updateImage'],
        ];
    }


    public function setImage(BeforeEntityPersistedEvent $event)
    {
        #D'abord tu ne me retourne rien plutôt qu'une erreur si l'entité récupérer n'est pas une instance de Product.
        if(!( $event->getEntityInstance() instanceof Product)){
            return;
        }
        #ici je récupére l'entité en cours de traitement
        $entity = $event->getEntityInstance();

        #ici je récupérer le nom du fichier donné par l'utilisatezur
        $name_file =$entity->getName();
        #$name_file = $_FILES(['product']['name']['image']);

        #ici je crée un uniqId pour ce fichier, il sera inserer en BDD pour éviter un nom doublon
        $uniqId = uniqid();

        #ici j'explose la super_global $_FILE pour récupérer l'extention du fichier
        #$extension = pathinfo($_FILES(['product']['name']['image']), PATHINFO_EXTENSION);

        #ici je récupére le chemin du dossier
      #  $project_dir = $this->appKernel->getProjectDir();

        #ici tu me déplace le fichier avec cette id dans mon dossier d'images
      #  move_uploaded_file($entity,$project_dir.'/public/uploads'.$uniqId.'.'.$extension );

        #ici tu me set le nouveu nom unique de mon image avant l'insertion en BDD
      #  $entity->setImage($uniqId.'.'.$extension);

        #Comme ça je saurais qu'elle image allez cherchez sur ma machine  quand j'aurais récupérer l'id unique de
        #l'image en BDD
    }

    public function updateImage(BeforeEntityUpdatedEvent $event)
    {
        #D'abord tu ne me retourne rien plutôt qu'une erreur si l'entité récupérer n'est pas une instance de Product.
        #Ca permet de ne pas executer le code d'apres
        if(!( $event->getEntityInstance() instanceof Product)){
            return;
        }
        #Si on est avant un update et que le champ image est rempli tu me joue le code
       // if($_FILES(['product']['name']['image']) != ''){

          //  $entity = $event->getEntityInstance();

          //  $name_file = $_FILES(['product']['name']['image']);

          //  $uniqId = uniqid();

            #$extension = pathinfo($_FILES(['product']['name']['image']), PATHINFO_EXTENSION);

            #  $project_dir = $this->appKernel->getProjectDir();

            #  move_uploaded_file($entity,$project_dir.'/public/uploads'.$uniqId.'.'.$extension );

            #  $entity->setImage($uniqId.'.'.$extension);
      //  }
    }
}
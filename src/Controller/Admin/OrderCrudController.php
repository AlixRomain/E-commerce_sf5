<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager,CrudUrlGenerator $crudUrlGenerator )
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    //configure action te permet d'ajouter/retirer des actions daans le tableau Crud de l'entité visé
    public function configureActions(Actions $actions): Actions
    {
        //Ici on rajoute la possibilité de dire pour les admins que la commande est en cours de préparation
        $updatePreparation = Action::new('updatePreparation','Préparation en cours', 'fas fa-box-open')->LinkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery','Livraison en cours','fas fa-truck')->LinkToCrudAction('updateDelivery');
        return $actions
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('index', 'detail')
            ->remove('index', 'delete');
    }
    //Script rattaché à l'action updatePreparation
    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order ->setState(2);
        $this->entityManager->flush();
        //Ici on peut rajouter un message Flash
        $this->addFlash('notice', "<span style='color:green;'><strong>La commande ".$order->getReference()." <u>est bien en cours de préparation.</u>  </strong></span>");
        //Ici on envoi un mail au client pour lui dire que ça commande est en cours de préparation
        $mail = new Mail();
        $content = 'Chere '.$order->getUser()->getFirstName().'<br> Nos équipes sont sur le cou <br> Votre commande ".$order->getReference()." est en cours de préparation par nos équipes. <br><br>';
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(),' Mon site | E-commerce commande en cours de préparation ',$content);
        //Ici on paramètre le lieux d'atterissage après l'action. REROUTAGE URL ADMIN
        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
        return $this->redirect($url);
    }

    //Script rattaché à l'action updateDelivery
    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order ->setState(3);
        $this->entityManager->flush();
        //Ici on peut rajouter un message Flash
        $this->addFlash('notice', "<span style='color:orange;'><strong>La commande ".$order->getReference()." <u>est bien en cours de livraison.</u>  </strong></span>");
        //Ici on envoi un mail au client pour lui dire que ça commande est en cours de préparation
        $mail = new Mail();
        $content = 'Chere '.$order->getUser()->getFirstName().'<br> 3! 2! 1!... Partez! <br> Votre commande ".$order->getReference()." est en cours de livraison. <br><br>';
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(),' Mon site | E-commerce commande en cours de préparation ',$content);

        //Ici on paramètre le lieux d'atterissage après l'action. REROUTAGE URL ADMIN
        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
        return $this->redirect($url);
    }

    //Ici nous demandons à l'affichage de nous trier par ordre d'ID Décroissant
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user.getFullName', 'Demandeur'),
            TextEditorField::new('deliverye', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice','Coût transport')->setCurrency('EUR'),
            ChoiceField::new('state', 'Etat')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' =>2,
                'Livraison en cours' =>3,

            ]),
            ArrayField::new('orderDetails')->hideOnIndex()
        ];
    }

}

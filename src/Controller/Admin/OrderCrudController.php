<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    //configure action te permet d'ajouter/retirer des actions daans le tableau Crud de l'entité visé
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail')
            ->remove('index', 'delete');
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
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice','Coût transport')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payée'),
            ArrayField::new('orderDetails')->hideOnIndex()
        ];
    }

}

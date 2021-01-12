<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('title', 'Titre du header'),
            TextareaField::new('content', 'Contenu'),
            TextField::new('btnTitle', 'Titre du bouton'),
            TextField::new('btnUrl', 'Url à atteindre'),
            ImageField::new('illustration', 'Ajouter une image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

        ];
    }

}

<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Ajouter un nom au produit'),
            TextField::new('subtitle', 'Ajouter un sous titre'),
            TextEditorField::new('description', 'Description du produit'),
            ImageField::new('image', 'Ajouter une image'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
        ];
    }

}

<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
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
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('subtitle', 'Ajouter un sous titre'),
            TextEditorField::new('description', 'Description du produit'),
            #ImageField::new('image', 'Ajouter une image')
               #->setUploadDir('uploads')
               #ici on diy à sf de ne pas chercher à le mapper avec l'entité et qu'il n'et pas requis ce champ
              # ->setFormTypeOptions(['mapped'=> false, 'required'=> false]),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            AssociationField::new('category', 'A qu\'elle catégorie souhaitez-vous rattacher ce produit?')
        ];
    }

}

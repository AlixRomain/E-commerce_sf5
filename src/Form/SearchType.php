<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class,[
                'label'   => 'Rechercher',
                'required'=> false,
                'attr'    => [
                    'placeholder' =>'Votre recherche',
                     'class' =>'form-control-sm'

                ]
            ])
//Entity type permet de lié un champ à une ENTITE, TRES utile pour des cases à cochez,
            ->add('categories', EntityType::class,[
                'label'   => false,
                'multiple'=> true,
                'required'=> false,
                'class'   => Category::class,
                'expanded'=> true
            ])

            ->add('submit', SubmitType::class,[
                'label'   => 'Filtrer',
                'attr'    => [
                    'class' =>'btn-block btn-info'
                ]
            ])
        ;
    }

    #Cette méthode nous permets de configurer des options
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method'    => 'GET',
            'crsf_protection' => false
        ]);
    }
    #Cette méthode nous permets de fixer un prefix dans l'url, ici on ne retourne rien pour éviter qu'elle ne retourne ''
    public function getBlockPrefix()
    {
        return '';
    }
}
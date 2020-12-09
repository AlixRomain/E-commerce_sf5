<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label'   => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Martin'],
                'required' => true,
            ])
            ->add('lastname',TextType::class, [
                'label'   => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Dupont'],
                'required' => true,
            ])
            ->add('city',TextType::class, [
                'label'   => 'Ville',
                'attr' => [
                    'placeholder' => 'Paris'],
                'required' => true,
            ])
            ->add('adress',TextType::class, [
                'label'   => 'Adresse',
                'attr' => [
                    'placeholder' => '5 rue des lylas'],
                'required' => true,
            ])
            ->add('postal',TextType::class, [
                'label'   => 'Code Postal ',
                'attr' => [
                    'placeholder' => '75000'],
                'constraints' => new Length([
                    'min' => 5,
                    'max' => 5
                ]),
                'required' => true,
            ])
            ->add('phone',TextType::class, [
                'label'   => 'Numéro de téléphone',
                'constraints' => new Length([
                    'min' => 10,
                    'max' => 10
                ]),
                'required' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label'=> 'Validez',
                'attr' => [
                    'class' => 'btn-block btn-info',
                    ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}

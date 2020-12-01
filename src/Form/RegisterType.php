<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstname',TextType::class, [
                'attr' => [
                    'placeholder' => 'Martin'],
                'label'=> 'Votre prenom',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 15
                 ])
            ])
            ->add('lastname',TextType::class, [
                'attr' => [
                    'placeholder' => 'Dupont'],
                'label'=> 'Votre nom',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 15
                ])
            ])
            ->add('email',EmailType::class, [
                'attr' => [
                    'placeholder' => 'martin@dupont.fr'],
                'label'=> 'Vueillez saisir votre email',
                'constraints' => new Length([
                    'min' => 6,
                    'max' => 60
                ])
            ])
            #Premiere solution avec le mapped à false
            #->add('password', PasswordType::class, [
                #'attr' => [
                #'placeholder' => 'pass'],
                #'label'=> 'Entrer votre mot de passe'
            #])
            #->add('password_confirm', PasswordType::class, [
                #'attr' => [
                #'placeholder' => 'pass'],
                #'label'=> 'Vueillez confirmer votre mot de  passe',
                #'mapped'=> false,
             #])
                #2 eme solution avec le repeatedType,
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Entrer votre mot de  passe',
                    'attr' => [
                        'placeholder' => 'Mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Vueillez confirmer votre mot de  passe',
                    'attr' => [
                        'placeholder' => 'Confirmation de votre mot de passe'],
                ],
            ])
            ->add('submit', SubmitType::class, [
            'label'=> 'Validez',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

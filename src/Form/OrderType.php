<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('addresses', EntityType::class,[
                'label'   => 'Choisissez une adresse de livraison',
                'multiple'=> false,
                'required'=> true,
                'class'   => Address::class,
                'choices' => $user->getAddresses(),
                'expanded'=> true
            ])

            ->add('carriers', EntityType::class,[
                'label'   => 'Choisissez votre transporteur',
                'multiple'=> false,
                'required'=> true,
                'class'   => Carrier::class,
                'expanded'=> true
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Validez ma commande',
                'attr' => [
                    'class' => 'btn-block btn-success',
                ],
            ])
        ;
    }

    //ici nous devons déclarer les options qui apparaîtons dans le $option ci-dessus.
    //les datas'user' sont retournés par le controller OrderController
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array(),
        ]);
    }
}

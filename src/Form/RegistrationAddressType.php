<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'address',
                TextType::class,
                ['label' => 'Adresse ligne 1']
            )
            ->add(
                'address2',
                TextType::class,
                ['label' => 'Adresse ligne 2']
            )
            ->add(
                'zipcode',
                TextType::class,
                ['label' => 'Code postal']
            )
            ->add(
                'city',
                TextType::class,
                ['label' => 'Ville']
            )
            ->add(
                'country',
                TextType::class,
                ['label' => 'Pays']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}

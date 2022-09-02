<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditInfosProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                ['label' => 'Prénom']
            )
            ->add(
                'lastname',
                TextType::class,
                ['label' => 'Nom']
            )
            ->add('address', RegistrationAddressType::class, [
                'label' => 'Adresse',
                'required'  => true,
            ])
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => 'Téléphone',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Un mot de passe est requis.',
                        ]),
                        new Regex([
                            'pattern' => '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
                            'match' => true,
                            'message' => 'Votre numéro de téléphone n\'est pas valide.',
                        ])
                    ],
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

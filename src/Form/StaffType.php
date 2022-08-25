<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'     => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label'     => 'Nom',
            ])
            ->add('birthdate', DateType::class, [
                'label'     => 'Date de naissance',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('photo', FileType::class, [
                'label'     => 'Photo du staff',
                'mapped' => false,
                'required' => is_null($builder->getData()->getId()),
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                            "image/gif",
                            "image/webp"
                        ],
                        'mimeTypesMessage' => 'Merci de selectionner des images valides. (JPG, PNG, JPEG, GIF, WEBP) de moins de 2Mo.',
                    ])
                ],
            ])
            ->add('nation', CountryType::class, [
                'label'     => 'Nationalité',
                'preferred_choices' => ['FR', 'DE', 'AR', 'BR'],
            ])
            ->add('cityOfBirth', TextType::class, [
                'label'     => 'Ville de naissance',
            ])
            ->add('role')
            ->add('team', EntityType::class, [
                "class" => Team::class,
                'label'     => 'Equipe',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}

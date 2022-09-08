<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductSize;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'     => 'Nom',
            ])
            ->add('description', TextType::class, [
                'label'     => 'Description',
            ])
            ->add('thumbnail', FileType::class, [
                'label'     => 'Image de l\'article',
                'mapped' => false,
                'required' => is_null($builder->getData()->getId()),
                'constraints' => [
                    new File([
                        'maxSize' => '5048k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                            "image/gif",
                            "image/webp"
                        ],
                        'mimeTypesMessage' => 'Merci de selectionner des images valides. (JPG, PNG, JPEG, GIF, WEBP) de moins de 5Mo.',
                    ])
                ],
            ])
            ->add('price', NumberType::class, [
                'label'     => 'Prix de l\'article',
            ])
            ->add('category')

            // ->add('sizes', EntityType::class, [
            //     'label'     => "Tailles disponibles",
            //     'required' => false,
            //     'class' => ProductSize::class,
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => [
            //         'class' => "d-flex flex-wrap gap-4"
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

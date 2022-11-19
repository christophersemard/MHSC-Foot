<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductSize;
use PhpParser\Parser\Multiple;
use App\Entity\ProductCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'label'     => false,
                'required' => false,
                'class' => ProductCategory::class,
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('minPrice', NumberType::class, [
                'label'     => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Minimum"
                ]
            ])
            ->add('maxPrice', NumberType::class, [
                'label'     => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Maximum"
                ]
            ])
            // ->add('sizes', EntityType::class, [
            //     'label'     => false,
            //     'required' => false,
            //     'class' => ProductSize::class,
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => [
            //         'class' => "d-flex flex-wrap input-sizes-two-column"
            //     ]
            // ]);
            // ->add('color')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}

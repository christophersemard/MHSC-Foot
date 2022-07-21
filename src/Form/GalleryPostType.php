<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GalleryPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('imagePosts', CollectionType::class, [
                'entry_type' => ImagePostType::class,
                'label' => false,
                'prototype'            => true,
                'allow_add'            => true,
                'allow_delete'        => true,
                'by_reference'         => false,
                'required'            => false,
            ])
            ->add('thumbnail')
            ->add('category');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

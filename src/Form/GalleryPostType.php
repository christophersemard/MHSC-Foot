<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class GalleryPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', CKEditorType::class)
            ->add('imagePosts', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'label'     => 'Ajouter des photos',
                'required'     => true,
                // 'constraints' => [
                //     new All([
                //         'constraints' => [
                //             new File([
                //                 'maxSize' => '2048k',
                //                 'mimeTypes' => [
                //                     "image/png",
                //                     "image/jpg",
                //                     "image/jpeg",
                //                     "image/gif",
                //                     "image/webp"
                //                 ],
                //                 'mimeTypesMessage' => 'Merci de selectionner des images valides. (JPG, PNG, JPEG, GIF, WEBP) de moins de 5Mo.',
                //             ])
                //         ],
                //     ]),
                // ]

            ])
            ->add('thumbnail', FileType::class, [
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
            ->add('category');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

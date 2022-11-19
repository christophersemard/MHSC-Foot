<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GalleryPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'     => 'Titre',
            ])
            ->add('content', TextareaType::class, [
                'required'  => false,
                'attr' => ['class' => 'tinymce'],
                'label'     => 'Contenu',
            ])
            ->add('imagePosts', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'label'     => 'Ajouter des photos',
                'required'     => is_null($builder->getData()->getId()),
                'constraints' => [
                    new All([
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
                                'mimeTypesMessage' => 'Merci de selectionner des images valides. (JPG, PNG, JPEG, GIF, WEBP) de moins de 5Mo.',
                            ])
                        ],
                    ]),
                ]
            ])
            ->add('thumbnail', FileType::class, [
                'label'     => 'Image de présentation',
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

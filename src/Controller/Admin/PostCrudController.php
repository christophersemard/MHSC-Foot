<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use App\Repository\TaskRepository;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }


    public function configureFields(string $pageName): iterable
    {


        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('type', 'Type'),
            ImageField::new('thumbnail', 'Image de présentation')
                ->setBasePath('uploads/thumbnails')
                ->setUploadDir('public/uploads/thumbnails')
                ->setUploadedFileNamePattern('[slug].[extension]'),
            TextField::new('title', 'Titre'),
            TextEditorField::new('content', 'Contenu')
                ->setNumOfRows(20)
                ->setTrixEditorConfig([
                    'blockAttributes' => [
                        'default' => ['tagName' => 'p'],
                        'heading1' => ['tagName' => 'h3'],
                    ],
                ]),
            AssociationField::new('category', 'Catégorie')->renderAsNativeWidget(),

        ];
    }
}

<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/actualites')]
class NewsController extends AbstractController
{
    #[Route('/', name: 'app_news')]
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request, CategoryRepository $categoryRepository): Response
    {
        $news = $paginator->paginate(
            $postRepository->findAllDesc(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
            'news' =>  $postRepository->findLast10(),
            'allNews' => $news,
            'currentCategory' => 'all',
            'categories' =>  $categoryRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'app_news_display')]
    public function display(Post $post, PostRepository $postRepository): Response
    {
        return $this->render('news/news.html.twig', [
            'controller_name' => 'NewsController',
            'post' => $post,
            'news' => $postRepository->findLast10(),
        ]);
    }
    #[Route('/categorie/{id}', name: 'app_news_category_display')]
    public function categoryDisplay(Category $category, PaginatorInterface $paginator, Request $request, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        $news = $paginator->paginate(
            $postRepository->findAllByCategory($category),
            // $category->getPosts(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
            'currentCategory' => $category,
            'news' => $postRepository->findLast10(),
            'allNews' => $news,
            'categories' =>  $categoryRepository->findAll(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Post;
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
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $news = $paginator->paginate(
            $postRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
            'news' =>  $postRepository->findLast10(),
            'allNews' => $news,
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
}

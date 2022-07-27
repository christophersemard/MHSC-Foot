<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actualites')]
class NewsController extends AbstractController
{
    #[Route('/', name: 'app_news')]
    public function index(): Response
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
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

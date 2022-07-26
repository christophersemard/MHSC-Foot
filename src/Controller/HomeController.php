<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository): Response
    {



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'mainPost' => $postRepository->findLast()[0],
            'news' => $postRepository->findLast10(),

        ]);
    }
}

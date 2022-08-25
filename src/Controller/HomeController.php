<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository, HttpClientInterface $client, Request $request): Response
    {
        // $response = $client->request(
        //     'GET',
        //     'https://api-football-v1.p.rapidapi.com/v3/teams',
        //     [
        //         'query' => ['id' => '82'],
        //         'headers' => [
        //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
        //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
        //         ]
        //     ]
        // );

        // $data = json_decode($response->getContent(), true);
        // dd($data);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'mainPost' => $postRepository->findLast()[0],
            'news' => $postRepository->findLast10(),

        ]);
    }
}

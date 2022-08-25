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
        // $responseTeam = $client->request(
        //     'GET',
        //     'https://api-football-v1.p.rapidapi.com/v3/leagues',
        //     [
        //         'query' => ['team' => '82'],
        //         'headers' => [
        //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
        //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
        //         ]
        //     ]
        // );
        // $team = json_decode($responseTeam->getContent(), true);
        // dd($team);

        // $responseStandings = $client->request(
        //     'GET',
        //     'https://api-football-v1.p.rapidapi.com/v3/standings',
        //     [
        //         'query' => ['season' => 2022, 'league' => '61'],
        //         'headers' => [
        //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
        //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
        //         ]
        //     ]
        // );
        // $standings = json_decode($responseStandings->getContent(), true);
        // dd($standings);

        // $responseFixtures = $client->request(
        //     'GET',
        //     'https://api-football-v1.p.rapidapi.com/v3/fixtures',
        //     [
        //         'query' => ['team' => 82, 'last' => 3],
        //         'headers' => [
        //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
        //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
        //         ]
        //     ]
        // );
        // $lastFixtures = json_decode($responseFixtures->getContent(), true);
        // dd($lastFixtures);

        // $responseFixtures = $client->request(
        //     'GET',
        //     'https://api-football-v1.p.rapidapi.com/v3/fixtures',
        //     [
        //         'query' => ['team' => 82, 'next' => 3],
        //         'headers' => [
        //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
        //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
        //         ]
        //     ]
        // );
        // $nextFixtures = json_decode($responseFixtures->getContent(), true);
        // dd($nextFixtures);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'mainPost' => $postRepository->findLast()[0],
            'news' => $postRepository->findLast10(),

        ]);
    }
}

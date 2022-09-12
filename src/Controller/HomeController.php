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


        $responseTeam = file_get_contents('../_JSON-requests/team-league.json');
        $mainTeam = json_decode($responseTeam, true);

        $responseStandings = file_get_contents('../_JSON-requests/standings.json');
        $standings = json_decode($responseStandings, true);
        $standingsRanks = $standings['response'][0]['league']['standings'][0];
        $teamStandings = [];
        foreach ($standingsRanks as $key => $team) {
            $teamStandings[] = $team['team'];
        }
        $indexStandings = array_search("82", array_column($teamStandings, 'id'));
        $sevenStandings = [];
        if ($indexStandings < 3) {
            $sevenStandings = array_slice($standingsRanks, 0, 7);
        } elseif ($indexStandings > (count($standingsRanks) - 4)) {
            $sevenStandings = array_slice($standingsRanks, (count($standingsRanks) - 7));
        } else {
            $sevenStandings = array_slice($standingsRanks, $indexStandings - 3,  7);
        }

        $responseFixtures = file_get_contents('../_JSON-requests/last-fixtures.json');
        $lastFixtures = json_decode($responseFixtures, true);

        $responseFixtures = file_get_contents('../_JSON-requests/next-fixtures.json');
        $nextFixtures = json_decode($responseFixtures, true);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'mainPost' => $postRepository->findLast()[0],
            'news' => $postRepository->findLast10(),
            'last4Videos' => $postRepository->findLast4Videos(),
            'last6Galleries' => $postRepository->findLast6Galleries(),
            'mainTeam' => $mainTeam,
            'standings' => $sevenStandings,
            'league' => $standings['response'][0]['league']['name'],
            'lastFixtures' => $lastFixtures['response'],
            'nextFixtures' => $nextFixtures['response'],


        ]);
    }
}

<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class APIService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getLastFixtures($slug)
    {
        $responseFixtures = $this->client->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v3/fixtures',
            [
                'query' => ['team' => $slug == 'pro' ? '82' : '1675', 'last' => 25],
                'headers' => [
                    'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
                    'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
                ]
            ]
        );
        $lastFixtures = json_decode($responseFixtures->getContent(), true);
        return $lastFixtures;
    }

    public function getNextFixtures($slug)
    {
        $responseFixtures = $this->client->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v3/fixtures',
            [
                'query' => ['team' => $slug == 'pro' ? '82' : '1675', 'next' => 25],
                'headers' => [
                    'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
                    'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
                ]
            ]
        );
        $nextFixtures = json_decode($responseFixtures->getContent(), true);
        return $nextFixtures;
    }


    public function getStandings($slug)
    {
        $responseStandings = $this->client->request(
            'GET',
            'https://api-football-v1.p.rapidapi.com/v3/standings',
            [
                'query' => ['season' => 2022, 'league' => $slug == 'pro' ? '61' : '64'],
                'headers' => [
                    'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
                    'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
                ]
            ]
        );
        $standings = json_decode($responseStandings->getContent(), true);
        $standingsRanks = $standings['response'][0]['league']['standings'][0];

        return $standingsRanks;
    }
}

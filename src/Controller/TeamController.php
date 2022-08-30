<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Repository\StaffRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoleStaffRepository;
use App\Repository\RolePlayerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/equipe')]
class TeamController extends AbstractController
{

    #[Route('/{slug}/effectif', name: 'app_team/squad')]
    public function squad($slug, TeamRepository $teamRepository, PlayerRepository $playerRepository, RolePlayerRepository $rolePlayerRepository): Response
    {
        // TODO : GET TEAM BY SLUG
        $team =  $teamRepository->findBySlug($slug);
        // TODO : GET PLAYERS BY TEAM
        $goalkeepers =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Gardien'), array('name' => 'ASC')));
        $defenders =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Défenseur'), array('name' => 'ASC')));
        $midfielders =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Milieu'), array('name' => 'ASC')));
        $forwards =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Attaquant'), array('name' => 'ASC')));
        // dd($goalkeepers);

        return $this->render('team/squad.html.twig', [
            'controller_name' => 'TeamController',
            'team' => $team,
            'goalkeepers' => $goalkeepers,
            'defenders' => $defenders,
            'midfielders' => $midfielders,
            'forwards' => $forwards,
        ]);
    }

    #[Route('/{slug}/staff', name: 'app_team/staff')]
    public function staff($slug, TeamRepository $teamRepository, StaffRepository $staffRepository, RoleStaffRepository $roleStaffRepository): Response
    {
        // TODO : GET TEAM BY SLUG
        $team =  $teamRepository->findBySlug($slug);
        // TODO : GET STAFFS BY TEAM
        $trainers =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Entraineur'), array('name' => 'ASC')));
        $technicians =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Staff technique'), array('name' => 'ASC')));
        $medicals =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Staff médical'), array('name' => 'ASC')));
        $others =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Autres'), array('name' => 'ASC')));
        // dd($goalkeepers);

        return $this->render('team/staff.html.twig', [
            'controller_name' => 'TeamController',
            'team' => $team,
            'trainers' => $trainers,
            'technicians' => $technicians,
            'medicals' => $medicals,
            'others' => $others,
        ]);
    }


    #[Route('/{slug}/calendrier-et-resultats', name: 'app_team/results')]
    public function results($slug, TeamRepository $teamRepository, PaginatorInterface $paginator, Request $request): Response
    {

        if ($slug == 'pro' || $slug == 'feminines') {

            // $responseFixtures = $client->request(
            //     'GET',
            //     'https://api-football-v1.p.rapidapi.com/v3/fixtures',
            //     [
            //         'query' => ['team' => 82, 'last' => 25],
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
            //         'query' => ['team' => 82, 'next' => 25],
            //         'headers' => [
            //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
            //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
            //         ]
            //     ]
            // );
            // $nextFixtures = json_decode($responseFixtures->getContent(), true);
            // dd($nextFixtures);

            $responseFixtures = file_get_contents('../_JSON-requests/last-fixtures-25.json');
            $lastFixtures = json_decode($responseFixtures, true);

            $responseFixtures = file_get_contents('../_JSON-requests/next-fixtures-25.json');
            $nextFixtures = json_decode($responseFixtures, true);

            $mergedFixtures = array_merge(array_reverse($lastFixtures['response']), $nextFixtures['response']);

            $fixtures = $paginator->paginate(
                $mergedFixtures, /* query NOT result */
                $request->query->getInt('page', 3), /*page number*/
                10 /*limit per page*/
            );


            // GET TEAM BY SLUG
            $team =  $teamRepository->findBySlug($slug);

            return $this->render('team/results.html.twig', [
                'controller_name' => 'TeamController',
                'team' => $team,
                'fixtures' => $fixtures,
            ]);
        } else {
            return new RedirectResponse('/');
        }
    }
    #[Route('/{slug}/classement', name: 'app_team/standings')]
    public function standings($slug, TeamRepository $teamRepository): Response
    {
        if ($slug == 'pro' || $slug == 'feminines') {
            $responseTeam = file_get_contents('../_JSON-requests/team-league.json');
            $mainTeam = json_decode($responseTeam, true);

            // $responseStandings = $client->request(
            //     'GET',
            //     'https://api-football-v1.p.rapidapi.com/v3/standings',
            //     [
            //         'query' => ['season' => 2022, 'league' => $slug == 'pro' ? '61' : '64'],
            //         'headers' => [
            //             'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com',
            //             'x-rapidapi-key' => '1f02f9ca6amshccfc632c7c05802p1a48cbjsnb7f356338428'
            //         ]
            //     ]
            // );
            // $standings = json_decode($responseStandings->getContent(), true);
            // dd($standings);

            $responseStandings = file_get_contents('../_JSON-requests/standings.json');
            $standings = json_decode($responseStandings, true);
            $standingsRanks = $standings['response'][0]['league']['standings'][0];

            // TODO : GET TEAM BY SLUG
            $team =  $teamRepository->findBySlug($slug);
            return $this->render('team/standings.html.twig', [
                'controller_name' => 'TeamController',
                'team' => $team,
                'mainTeam' => $mainTeam,
                'standings' => $standingsRanks,
            ]);
        } else {
            return new RedirectResponse('/');
        }
    }
}

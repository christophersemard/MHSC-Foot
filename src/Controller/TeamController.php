<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Repository\StaffRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoleStaffRepository;
use App\Repository\RolePlayerRepository;
use App\Service\APIService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/equipe')]
class TeamController extends AbstractController
{

    #[Route('/{slug}/effectif', name: 'app_team/squad')]
    public function squad($slug, TeamRepository $teamRepository, PlayerRepository $playerRepository, RolePlayerRepository $rolePlayerRepository): Response
    {
        $team =  $teamRepository->findBySlug($slug);

        $goalkeepers =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Gardien')));
        $defenders =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Défenseur')));
        $midfielders =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Milieu')));
        $forwards =  $playerRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Attaquant')));

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
        $team =  $teamRepository->findBySlug($slug);
        $trainers =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Entraineur'), array('name' => 'ASC')));
        $technicians =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Staff technique'), array('name' => 'ASC')));
        $medicals =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Staff médical'), array('name' => 'ASC')));
        $others =  $staffRepository->findByTeamAndRole($team, $roleStaffRepository->findOneBy(array('name' => 'Autres'), array('name' => 'ASC')));


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
    public function results($slug, TeamRepository $teamRepository, PaginatorInterface $paginator, Request $request, APIService $apiService): Response
    {

        if ($slug == 'pro' || $slug == 'feminines') {

            $lastFixtures = $apiService->getLastFixtures($slug);
            $nextFixtures = $apiService->getNextFixtures($slug);

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
    public function standings($slug, TeamRepository $teamRepository, HttpClientInterface $client, APIService $apiService): Response
    {
        if ($slug == 'pro' || $slug == 'feminines') {

            $mainTeam = $slug == "pro" ? 82 : 1675;

            $standings = $apiService->getStandings($slug);

            $team =  $teamRepository->findBySlug($slug);

            return $this->render('team/standings.html.twig', [
                'controller_name' => 'TeamController',
                'team' => $team,
                'mainTeam' => $mainTeam,
                'standings' => $standings,
            ]);
        } else {
            return new RedirectResponse('/');
        }
    }
}

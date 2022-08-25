<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Repository\PlayerRepository;
use App\Repository\RolePlayerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function staff($slug, TeamRepository $teamRepository, StaffRepository $staffRepository, RolePlayerRepository $rolePlayerRepository): Response
    {
        // TODO : GET TEAM BY SLUG
        $team =  $teamRepository->findBySlug($slug);
        // TODO : GET PLAYERS BY TEAM
        $staffs =  $staffRepository->findByTeamAndRole($team, $rolePlayerRepository->findOneBy(array('name' => 'Gardien'), array('name' => 'ASC')));
        // dd($goalkeepers);

        return $this->render('team/squad.html.twig', [
            'controller_name' => 'TeamController',
            'team' => $team,
            'staffs' => $staffs,
        ]);
    }


    #[Route('/{team}/calendrier-et-resultats', name: 'app_team/results')]
    public function results($team): Response
    {
        return $this->render('team/squad.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }
}

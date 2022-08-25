<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Repository\StaffRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoleStaffRepository;
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


    #[Route('/{team}/calendrier-et-resultats', name: 'app_team/results')]
    public function results($team): Response
    {
        return $this->render('team/squad.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }
}

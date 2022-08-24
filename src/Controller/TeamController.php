<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/equipe')]
class TeamController extends AbstractController
{

    #[Route('/{team}/effectif', name: 'app_team')]
    public function squad($team): Response
    {
        dd($team);
        // TODO : GET TEAM BY SLUG
        // TODO : GET PLAYERS BY TEAM

        return $this->render('team/squad.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }
}

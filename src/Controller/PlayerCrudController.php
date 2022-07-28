<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/joueurs')]
class PlayerCrudController extends AbstractController
{
    #[Route('/', name: 'app_player_crud_index', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository): Response
    {
        return $this->render('player_crud/index.html.twig', [
            'players' => $playerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_player_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayerRepository $playerRepository): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->add($player, true);

            return $this->redirectToRoute('app_player_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('player_crud/new.html.twig', [
            'player' => $player,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_player_crud_show', methods: ['GET'])]
    public function show(Player $player): Response
    {
        return $this->render('player_crud/show.html.twig', [
            'player' => $player,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_player_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Player $player, PlayerRepository $playerRepository): Response
    {
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->add($player, true);

            return $this->redirectToRoute('app_player_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('player_crud/edit.html.twig', [
            'player' => $player,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_player_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Player $player, PlayerRepository $playerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
            $playerRepository->remove($player, true);
        }

        return $this->redirectToRoute('app_player_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

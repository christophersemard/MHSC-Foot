<?php

namespace App\Controller;

use App\Repository\SinglePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SinglePageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'app_single_page')]
    public function index($slug, SinglePageRepository $singlePageRepository): Response
    {

        $singlePage =  $singlePageRepository->findBySlug($slug)[0];
        return $this->render('single_page/index.html.twig', [
            'controller_name' => 'SinglePageController',
            'singlePage' => $singlePage,
        ]);
    }
}

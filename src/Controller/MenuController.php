<?php

namespace App\Controller;

use App\Repository\SinglePageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SinglePageCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{

    public function pages(SinglePageCategoryRepository $singlePageCategoryRepository): Response
    {
        $clubPages = $singlePageCategoryRepository->findOneBy(array('name' => 'Club'), array('name' => 'ASC'));
        $fanPages = $singlePageCategoryRepository->findOneBy(array('name' => 'Supporters'), array('name' => 'ASC'));
        $otherPages = $singlePageCategoryRepository->findOneBy(array('name' => 'Autres'), array('name' => 'ASC'));


        return $this->render('partials/_header.html.twig', [
            'controller_name' => 'MenuController',
            'clubPages' => $clubPages->getSinglePages(),
            'fansPages' => $fanPages->getSinglePages(),
            'otherPages' => $otherPages->getSinglePages(),

        ]);
    }
}

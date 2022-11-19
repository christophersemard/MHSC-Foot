<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
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
            'clubPages' => $clubPages ? $clubPages->getSinglePages() : '',
            'fansPages' => $clubPages ? $fanPages->getSinglePages() : '',
            'otherPages' => $clubPages ? $otherPages->getSinglePages() : '',
        ]);
    }
}

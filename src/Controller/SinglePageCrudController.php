<?php

namespace App\Controller;

use App\Entity\SinglePage;
use App\Form\SinglePageType;
use App\Repository\SinglePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/administration/pages')]
class SinglePageCrudController extends AbstractController
{
    #[Route('/', name: 'app_single_page_crud_index', methods: ['GET'])]
    public function index(SinglePageRepository $singlePageRepository): Response
    {
        return $this->render('single_page_crud/index.html.twig', [
            'singlePages' => $singlePageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_single_page_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SinglePageRepository $singlePageRepository, SluggerInterface $slugger): Response
    {
        $singlePage = new SinglePage();
        $form = $this->createForm(SinglePageType::class, $singlePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $slug = strtolower($slugger->slug($title));
            $singlePage->setSlug($slug);

            $singlePageRepository->add($singlePage, true);

            return $this->redirectToRoute('app_single_page_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('single_page_crud/new.html.twig', [
            'singlePage' => $singlePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_single_page_crud_show', methods: ['GET'])]
    public function show(SinglePage $singlePage): Response
    {
        return $this->render('single_page_crud/show.html.twig', [
            'singlePage' => $singlePage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_single_page_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SinglePage $singlePage, SinglePageRepository $singlePageRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SinglePageType::class, $singlePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $singlePageRepository->add($singlePage, true);

            return $this->redirectToRoute('app_single_page_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('single_page_crud/edit.html.twig', [
            'singlePage' => $singlePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_single_page_crud_delete', methods: ['POST'])]
    public function delete(Request $request, SinglePage $singlePage, SinglePageRepository $singlePageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $singlePage->getId(), $request->request->get('_token'))) {
            $singlePageRepository->remove($singlePage, true);
        }

        return $this->redirectToRoute('app_single_page_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

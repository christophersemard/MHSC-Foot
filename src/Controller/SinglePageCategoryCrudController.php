<?php

namespace App\Controller;

use App\Entity\SinglePageCategory;
use App\Form\SinglePageCategoryType;
use App\Repository\SinglePageCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/categories-page')]
class SinglePageCategoryCrudController extends AbstractController
{
    #[Route('/', name: 'app_single_page_category_crud_index', methods: ['GET'])]
    public function index(SinglePageCategoryRepository $categoryRepository): Response
    {
        return $this->render('single_page_category_crud/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_single_page_category_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SinglePageCategoryRepository $categoryRepository): Response
    {
        $category = new SinglePageCategory();
        $form = $this->createForm(SinglePageCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoryRepository->add($category, true);
            return $this->redirectToRoute('app_single_page_category_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('single_page_category_crud/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_single_page_category_crud_show', methods: ['GET'])]
    public function show(SinglePageCategory $category): Response
    {
        return $this->render('single_page_category_crud/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_single_page_category_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SinglePageCategory $category, SinglePageCategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_single_page_category_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('single_page_category_crud/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_single_page_category_crud_delete', methods: ['POST'])]
    public function delete(Request $request, SinglePageCategory $category, SinglePageCategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_single_page_category_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

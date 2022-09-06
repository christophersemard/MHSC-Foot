<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\PostCategoryType;
use App\Repository\PostCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/categories-article')]
class PostCategoryCrudController extends AbstractController
{
    #[Route('/', name: 'app_post_category_crud_index', methods: ['GET'])]
    public function index(PostCategoryRepository $categoryRepository): Response
    {
        return $this->render('post_category_crud/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_category_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostCategoryRepository $categoryRepository): Response
    {
        $category = new PostCategory();
        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_post_category_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_category_crud/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_category_crud_show', methods: ['GET'])]
    public function show(PostCategory $category): Response
    {
        return $this->render('post_category_crud/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_category_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PostCategory $category, PostCategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_post_category_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_category_crud/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_category_crud_delete', methods: ['POST'])]
    public function delete(Request $request, PostCategory $category, PostCategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_post_category_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

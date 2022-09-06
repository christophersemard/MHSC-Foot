<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/categorie')]
class ProductCategoryCrudController extends AbstractController
{
    #[Route('/', name: 'app_product_category_crud_index', methods: ['GET'])]
    public function index(ProductCategoryRepository $categoryRepository): Response
    {
        return $this->render('product_category_crud/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_category_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductCategoryRepository $categoryRepository): Response
    {
        $category = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_product_category_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_category_crud/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_category_crud_show', methods: ['GET'])]
    public function show(ProductCategory $category): Response
    {
        return $this->render('product_category_crud/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_category_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductCategory $category, ProductCategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_product_category_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_category_crud/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_category_crud_delete', methods: ['POST'])]
    public function delete(Request $request, ProductCategory $category, ProductCategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_product_category_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

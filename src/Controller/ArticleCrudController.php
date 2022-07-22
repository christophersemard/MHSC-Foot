<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ArticlePostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/article')]
class ArticleCrudController extends AbstractController
{
    #[Route('/', name: 'app_article_crud_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('article_crud/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(ArticlePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setType('article');
            $postRepository->add($post, true);

            return $this->redirectToRoute('app_article_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_crud/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_crud_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('article_crud/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(ArticlePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post, true);

            return $this->redirectToRoute('app_article_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_crud/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_article_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

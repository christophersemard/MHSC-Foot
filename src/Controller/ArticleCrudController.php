<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ArticlePostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/administration/article')]
class ArticleCrudController extends AbstractController
{
    #[Route('/', name: 'app_article_crud_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('article_crud/index.html.twig', [
            'posts' => $postRepository->findAllArticle(),
        ]);
    }

    #[Route('/new', name: 'app_article_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $form = $this->createForm(ArticlePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $thumbnail = $form->get('thumbnail')->getData();
            $originalFilename = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = '../uploads/thumbnails/' . $safeFilename . '-' . uniqid() . '.' . $thumbnail->guessExtension();
            $thumbnail->move(
                $this->getParameter('thumbnails_directory'),
                $newFilename
            );

            $post->setThumbnail($newFilename);

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
    public function edit(Request $request, Post $post, PostRepository $postRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticlePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $originalFilename = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = '../uploads/thumbnails/' . $safeFilename . '-' . uniqid() . '.' . $thumbnail->guessExtension();
                $thumbnail->move(
                    $this->getParameter('thumbnails_directory'),
                    $newFilename
                );
                $post->setThumbnail($newFilename);
            }

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

<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\VideoPostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/administration/video')]
class VideoCrudController extends AbstractController
{
    #[Route('/', name: 'app_video_crud_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('video_crud/index.html.twig', [
            'posts' => $postRepository->findAllVideo(),
        ]);
    }

    #[Route('/new', name: 'app_video_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $form = $this->createForm(VideoPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $thumbnail = $form->get('thumbnail')->getData();
            $originalFilename = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = './uploads/thumbnails/' . $safeFilename . '-' . uniqid() . '.' . $thumbnail->guessExtension();
            $thumbnail->move(
                $this->getParameter('thumbnails_directory'),
                $newFilename
            );

            $post->setThumbnail($newFilename);

            $post->setType('video');

            $postRepository->add($post, true);

            return $this->redirectToRoute('app_video_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('video_crud/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_video_crud_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('video_crud/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_video_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(VideoPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post, true);

            return $this->redirectToRoute('app_video_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('video_crud/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_video_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_video_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

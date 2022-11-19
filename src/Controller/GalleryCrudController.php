<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\ImagePost;
use App\Form\GalleryPostType;
use App\Repository\PostRepository;
use App\Repository\ImagePostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/administration/gallerie')]
class GalleryCrudController extends AbstractController
{
    #[Route('/', name: 'app_gallery_crud_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts =  $postRepository->findAllGallery();
        return $this->render('gallery_crud/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'app_gallery_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $form = $this->createForm(GalleryPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('imagePosts')->getData();
            foreach ($images as $image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = '../uploads/gallery/' . $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('gallery_directory'),
                    $newFilename
                );
                $img = new ImagePost();
                $img->setImageUrl($newFilename);
                $post->addImagePost($img);
            }

            $thumbnail = $form->get('thumbnail')->getData();
            $originalFilename = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = '../uploads/thumbnails/' . $safeFilename . '-' . uniqid() . '.' . $thumbnail->guessExtension();
            $thumbnail->move(
                $this->getParameter('thumbnails_directory'),
                $newFilename
            );
            $post->setThumbnail($newFilename);

            $post->setType('gallery');
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_gallery_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gallery_crud/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gallery_crud_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('gallery_crud/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gallery_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(GalleryPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $images = $form->get('imagePosts')->getData();
            if ($images) {
                foreach ($images as $image) {

                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = '../uploads/gallery/' . $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                    $image->move(
                        $this->getParameter('gallery_directory'),
                        $newFilename
                    );
                    $img = new ImagePost();
                    $img->setImageUrl($newFilename);

                    $post->addImagePost($img);
                }
            }


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

            return $this->redirectToRoute('app_gallery_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gallery_crud/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gallery_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_gallery_crud_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/supprime/image/{id}', name: 'app_gallery_crud_delete_image')]
    public function deleteImage(EntityManagerInterface $entityManager, Request $request, ImagePost $imagePost)
    {
        $parameters = json_decode($request->getContent(), true);
        $token = $parameters['_token'];
        if ($this->isCsrfTokenValid('delete' . $imagePost->getId(), $token)) {
            $nom = $imagePost->getImageUrl();
            $nom = str_replace("../uploads/gallery/", "", $nom);
            unlink($this->getParameter('gallery_directory').'/'.$nom);
            $entityManager->remove($imagePost);
            $entityManager->flush();
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalid'], 400);
        }
    }
}

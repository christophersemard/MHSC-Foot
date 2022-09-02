<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditInfosProfileType;
use App\Form\EditPasswordProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfileController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }



    #[Route('/mon-compte/infos', name: 'app_profile/edit_infos')]
    public function edit_infos(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditInfosProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('success', 'Informations personnelles modifiées.');
        return $this->render('profile/edit_infos.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/mon-compte/mot-de-passe', name: 'app_profile/edit_password')]
    public function edit_password(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(EditPasswordProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($user);
            $oldPassword = $form->get('oldPassword')->getData();
            if (!$userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('danger', 'Mot de passe actuel invalide');
                return $this->redirectToRoute('app_profile/edit_password', [], Response::HTTP_SEE_OTHER);
            }

            // encode the plain password
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success', 'Mot de passe modifié');
            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

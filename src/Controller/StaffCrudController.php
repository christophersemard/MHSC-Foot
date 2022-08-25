<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffType;
use App\Repository\StaffRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/administration/staff')]
class StaffCrudController extends AbstractController
{
    #[Route('/', name: 'app_staff_crud_index', methods: ['GET'])]
    public function index(StaffRepository $staffRepository): Response
    {
        return $this->render('staff_crud/index.html.twig', [
            'staffs' => $staffRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_staff_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StaffRepository $staffRepository, SluggerInterface $slugger): Response
    {
        $staff = new Staff();
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = '../uploads/photos/' . $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
            $photo->move(
                $this->getParameter('photos_directory'),
                $newFilename
            );
            $staff->setPhoto($newFilename);

            $staffRepository->add($staff, true);

            return $this->redirectToRoute('app_staff_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('staff_crud/new.html.twig', [
            'staff' => $staff,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_staff_crud_show', methods: ['GET'])]
    public function show(Staff $staff): Response
    {
        return $this->render('staff_crud/show.html.twig', [
            'staff' => $staff,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_staff_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Staff $staff, StaffRepository $staffRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = '../uploads/photos/' . $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                $photo->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
                $staff->setPhoto($newFilename);
            }

            $staffRepository->add($staff, true);

            return $this->redirectToRoute('app_staff_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('staff_crud/edit.html.twig', [
            'staff' => $staff,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_staff_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Staff $staff, StaffRepository $staffRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $staff->getId(), $request->request->get('_token'))) {
            $staffRepository->remove($staff, true);
        }

        return $this->redirectToRoute('app_staff_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}

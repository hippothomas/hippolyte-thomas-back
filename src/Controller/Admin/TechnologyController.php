<?php

namespace App\Controller\Admin;

use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnologyController extends AbstractController
{
    #[Route('/admin/technologies', name: 'admin_technologies')]
    public function index(TechnologyRepository $repo): Response
    {
        return $this->render('admin/technology/index.html.twig', [
            'technologies' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/technology/new', name: 'admin_technology_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $technology = new Technology();

        $form = $this->createForm(TechnologyType::class, $technology);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($technology);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> La technologie <strong>{$technology->getName()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_technology_edit', [
                'id' => $technology->getId(),
            ]);
        }

        return $this->render('admin/technology/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/technology/{id}/edit', name: 'admin_technology_edit')]
    public function edit(Technology $technology, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TechnologyType::class, $technology);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($technology);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> La technologie <strong>{$technology->getName()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('admin_technology_edit', [
                'id' => $technology->getId(),
            ]);
        }

        return $this->render('admin/technology/edit.html.twig', [
            'form' => $form->createView(),
            'technology' => $technology,
        ]);
    }

    #[Route('/admin/technology/{id}/delete', name: 'admin_technology_delete')]
    public function delete(Technology $technology, Request $request, EntityManagerInterface $manager): Response
    {
        $confirm = (bool) $request->query->get('confirm');

        if ($confirm) {
            $manager->remove($technology);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> La technologie <strong>{$technology->getName()}</strong> a bien été supprimée !"
            );

            return $this->redirectToRoute('admin_technologies');
        }

        return $this->render('admin/technology/delete.html.twig', [
            'technology' => $technology,
        ]);
    }
}

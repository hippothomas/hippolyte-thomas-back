<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProjectController extends AbstractController
{
    #[Route('/admin/projects', name: 'admin_projects')]
    public function index(ProjectRepository $repo): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/project/new', name: 'admin_project_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($project->getPictures() as $picture) {
                $picture->setProject($project);
                $manager->persist($picture);
            }
            foreach ($project->getTechnologies() as $techno) {
                $techno->addProject($project);
                $manager->persist($techno);
            }

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le projet <strong>{$project->getName()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_project', [
                'id' => $project->getId(),
            ]);
        }

        return $this->render('admin/project/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/project/{id}/edit', name: 'admin_project')]
    public function edit(Project $project, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($project->getPictures() as $picture) {
                $picture->setProject($project);
                $manager->persist($picture);
            }
            foreach ($project->getTechnologies() as $techno) {
                $techno->addProject($project);
                $manager->persist($techno);
            }

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le projet <strong>{$project->getName()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('admin_project', [
                'id' => $project->getId(),
            ]);
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/admin/project/{id}/delete', name: 'admin_project_delete')]
    public function delete(Project $project, Request $request, EntityManagerInterface $manager): Response
    {
        $confirm = (bool) $request->query->get('confirm');

        if ($confirm) {
            foreach ($project->getPictures() as $picture) {
                $manager->remove($picture);
            }
            $manager->remove($project);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le projet <strong>{$project->getName()}</strong> a bien été supprimé !"
            );

            return $this->redirectToRoute('admin_projects');
        }

        return $this->render('admin/project/delete.html.twig', [
            'project' => $project,
        ]);
    }
}

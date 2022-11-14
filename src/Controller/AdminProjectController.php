<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProjectController extends AbstractController
{
    #[Route('/admin/projects', name: 'admin_projects')]
    public function index(ProjectRepository $repo): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/project/{id}', name: 'admin_project')]
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

            return $this->redirectToRoute("admin_project", [
                "id" => $project->getId()
            ]);
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}

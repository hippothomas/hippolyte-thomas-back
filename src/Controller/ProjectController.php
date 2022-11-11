<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    #[Route('/api/projects', name: 'api_projects')]
    public function getProjects(ProjectRepository $project, SerializerInterface $serializer): JsonResponse
    {
        $data = $project->findAll();
        $json = $serializer->serialize($data, 'json', ['groups' => ['project', 'technology', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    
    #[Route('/api/project/{slug}', name: 'api_project')]
    public function getProject(Project $project, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($project, 'json', ['groups' => ['project', 'technology', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

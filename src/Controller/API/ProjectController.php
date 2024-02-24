<?php

namespace App\Controller\API;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectController extends AbstractController
{
    #[Route('/api/projects', name: 'api_projects_v1', methods: ['GET'])]
    #[Route('/v2/projects', name: 'api_projects', methods: ['GET'])]
    public function getProjects(ProjectRepository $project, SerializerInterface $serializer): JsonResponse
    {
        $data = $project->findAllPublished();
        $json = $serializer->serialize($data, 'json', ['groups' => ['project', 'project_details', 'technology', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/project/{slug}', name: 'api_project_v1', methods: ['GET'])]
    #[Route('/v2/projects/{slug}', name: 'api_project', methods: ['GET'])]
    public function getProject(Project $project, SerializerInterface $serializer): JsonResponse
    {
        // Check if the project is published
        if (null === $project->getPublished() || $project->getPublished() > new \DateTime()) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Page not found');
        }
        $json = $serializer->serialize($project, 'json', ['groups' => ['project', 'project_details', 'technology', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

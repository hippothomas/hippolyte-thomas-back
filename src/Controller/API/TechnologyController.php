<?php

namespace App\Controller\API;

use App\Entity\Technology;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TechnologyController extends AbstractController
{
    #[Route('/api/technologies', name: 'api_technologies_v1', methods: ['GET'])]
    #[Route('/v2/technologies', name: 'api_technologies', methods: ['GET'])]
    public function getTechnologies(TechnologyRepository $technology, SerializerInterface $serializer): JsonResponse
    {
        $data = $technology->findAll();
        $json = $serializer->serialize($data, 'json', ['groups' => ['technology']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/technology/{slug}', name: 'api_technology_v1', methods: ['GET'])]
    #[Route('/v2/technologies/{slug}', name: 'api_technology', methods: ['GET'])]
    public function getTechnology(Technology $technology, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($technology, 'json', ['groups' => ['technology', 'technology_details', 'project']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

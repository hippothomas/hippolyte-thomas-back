<?php

namespace App\Controller;

use App\Repository\AboutMeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AboutMeController extends AbstractController
{
    #[Route('/api/about-me', name: 'api_about_me_v1', methods: ['GET'])]
    #[Route('/v2/about-me', name: 'api_about_me', methods: ['GET'])]
    public function getAboutMe(AboutMeRepository $about_me, SerializerInterface $serializer): JsonResponse
    {
        $data = $about_me->findAll();
        $json = $serializer->serialize($data, 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

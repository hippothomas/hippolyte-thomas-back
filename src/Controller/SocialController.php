<?php

namespace App\Controller;

use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SocialController extends AbstractController
{
    #[Route('/api/socials', name: 'api_socials', methods: ['GET'])]
    public function getSocials(SocialRepository $social, SerializerInterface $serializer): JsonResponse
    {
        $data = $social->findAll();
        $json = $serializer->serialize($data, 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

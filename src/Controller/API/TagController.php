<?php

namespace App\Controller\API;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TagController extends AbstractController
{
    #[Route('/v2/tags', name: 'api_tags', methods: ['GET'])]
    public function getTags(TagRepository $tag, SerializerInterface $serializer): JsonResponse
    {
        $data = $tag->findAll();
        $json = $serializer->serialize($data, 'json', ['groups' => ['tag']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/v2/tags/{slug}', name: 'api_tag', methods: ['GET'])]
    public function getTag(Tag $tag, SerializerInterface $serializer): JsonResponse
    {
        // Remove posts that are not published yet
        $posts = $tag->getPosts();
        foreach ($posts as $post) {
            if (null === $post->getPublished() || $post->getPublished() > new \DateTime()) {
                $tag->removePost($post);
            }
        }

        $json = $serializer->serialize($tag, 'json', ['groups' => ['tag', 'tag_details', 'post_summary', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

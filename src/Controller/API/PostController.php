<?php

namespace App\Controller\API;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    #[Route('/v2/posts', name: 'api_posts', methods: ['GET'])]
    public function getPosts(PostRepository $post, SerializerInterface $serializer): JsonResponse
    {
        $data = $post->findAllPublished();
        $json = $serializer->serialize($data, 'json', ['groups' => ['post_summary', 'tag', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/v2/posts/{slug}', name: 'api_post', methods: ['GET'])]
    public function getPost(Post $post, SerializerInterface $serializer): JsonResponse
    {
        // Check if the post is published
        if (null === $post->getPublished() || $post->getPublished() > new \DateTime()) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Page not found');
        }

        $json = $serializer->serialize($post, 'json', ['groups' => ['post', 'post_details', 'tag', 'media']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

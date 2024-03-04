<?php

namespace App\Tests\Controller\API;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Tests\ApiTestCase;

class PostControllerTest extends ApiTestCase
{
    public function testPosts(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/posts'.self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        $this->assertCount(5, $content);

        foreach ($content as $element) {
            $this->assertIsObject($element);
            $this->assertObjectMatchClass($element, Post::class, ['post_summary', 'tag', 'media']);

            // Check that every post is published
            try {
                /** @var \stdClass $element */
                $published = new \DateTime($element->published);
            } catch (\Exception $e) {
                $this->fail('Invalid published date.');
            }
            $this->assertLessThan(time(), $published->getTimestamp());
        }
    }

    public function testPostNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/posts/xxxxx'.self::API_KEY);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testPostNotPublished(): void
    {
        $client = static::createClient();

        $postRepository = static::getContainer()->get(PostRepository::class);
        if (!$postRepository instanceof PostRepository) {
            $this->fail('Fail to load PostRepository.');
        }
        $post = $postRepository->findOneBy(['published' => null]);
        if (!$post instanceof Post) {
            $this->fail('Fail get unpublished Post from database.');
        }

        $client->request('GET', '/v2/posts/'.$post->getSlug().self::API_KEY);
        $this->assertResponseStatusCodeSame(404);
        $this->assertResponseFormatSame('json');
    }

    public function testPost(): void
    {
        $client = static::createClient();

        $postRepository = static::getContainer()->get(PostRepository::class);
        if (!$postRepository instanceof PostRepository) {
            $this->fail('Fail to load PostRepository.');
        }
        $posts = $postRepository->findAllPublished();
        if (!is_array($posts) || empty($posts[0])) {
            $this->fail('Fail get published Posts from database.');
        }

        $client->request('GET', '/v2/posts/'.$posts[0]->getSlug().self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        $post = (object) $content;
        $this->assertObjectMatchClass($post, Post::class, ['post', 'post_details', 'tag', 'media']);

        // Check that the post is published
        try {
            $published = new \DateTime($post->published);
        } catch (\Exception $e) {
            $this->fail('Invalid published date.');
        }
        $this->assertLessThan(time(), $published->getTimestamp());
    }
}

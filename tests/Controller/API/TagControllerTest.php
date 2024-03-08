<?php

namespace App\Tests\Controller\API;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Tests\ApiTestCase;

class TagControllerTest extends ApiTestCase
{
    public function testTags(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/tags'.self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        foreach ($content as $element) {
            $this->assertIsObject($element);
            $this->assertObjectMatchClass($element, Tag::class, ['tag']);
        }
    }

    public function testTagNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/tags/xxxxx'.self::API_KEY);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testTag(): void
    {
        $client = static::createClient();

        $tagRepository = static::getContainer()->get(TagRepository::class);
        if (!$tagRepository instanceof TagRepository) {
            $this->fail('Fail to load TagRepository.');
        }
        $tag = $tagRepository->findOneBy([]);
        if (!$tag instanceof Tag) {
            $this->fail('Fail get Tags from database.');
        }

        $client->request('GET', '/v2/tags/'.$tag->getSlug().self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        $tag = (object) $content;
        $this->assertObjectMatchClass($tag, Tag::class, ['tag', 'tag_details', 'post_summary', 'media']);

        // Check that the posts are published
        foreach ($tag->posts as $post) {
            try {
                $published = new \DateTime($post->published);
            } catch (\Exception $e) {
                $this->fail('Invalid published date.');
            }
            $this->assertLessThan(time(), $published->getTimestamp());
        }
    }
}

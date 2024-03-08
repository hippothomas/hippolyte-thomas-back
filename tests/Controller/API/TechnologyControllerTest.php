<?php

namespace App\Tests\Controller\API;

use App\Entity\Technology;
use App\Repository\TechnologyRepository;
use App\Tests\ApiTestCase;

class TechnologyControllerTest extends ApiTestCase
{
    public function testTechnologies(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/technologies'.self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        foreach ($content as $element) {
            $this->assertIsObject($element);
            $this->assertObjectMatchClass($element, Technology::class, ['technology']);
        }
    }

    public function testTechnologyNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/technologies/xxxxx'.self::API_KEY);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testTechnology(): void
    {
        $client = static::createClient();

        $technologyRepository = static::getContainer()->get(TechnologyRepository::class);
        if (!$technologyRepository instanceof TechnologyRepository) {
            $this->fail('Fail to load TechnologyRepository.');
        }
        $technology = $technologyRepository->findOneBy([]);
        if (!$technology instanceof Technology) {
            $this->fail('Fail get Technologies from database.');
        }

        $client->request('GET', '/v2/technologies/'.$technology->getSlug().self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        $technology = (object) $content;
        $this->assertObjectMatchClass($technology, Technology::class, ['technology', 'technology_details', 'project']);

        // Check that the projects are published
        foreach ($technology->projects as $project) {
            try {
                $published = new \DateTime($project->published);
            } catch (\Exception $e) {
                $this->fail('Invalid published date.');
            }
            $this->assertLessThan(time(), $published->getTimestamp());
        }
    }
}

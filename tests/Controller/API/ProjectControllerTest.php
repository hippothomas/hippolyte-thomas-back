<?php

namespace App\Tests\Controller\API;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Tests\ApiTestCase;

class ProjectControllerTest extends ApiTestCase
{
    public function testProjects(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/projects'.self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        $this->assertCount(5, $content);

        foreach ($content as $element) {
            $this->assertIsObject($element);
            $this->assertObjectMatchClass($element, Project::class, ['project', 'project_details', 'technology', 'media']);

            // Check that every project is published
            try {
                /** @var \stdClass $element */
                $published = new \DateTime($element->published);
            } catch (\Exception $e) {
                $this->fail('Invalid published date.');
            }
            $this->assertLessThan(time(), $published->getTimestamp());
        }
    }

    public function testProjectNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/projects/xxxxx'.self::API_KEY);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testProjectNotPublished(): void
    {
        $client = static::createClient();

        $projectRepository = static::getContainer()->get(ProjectRepository::class);
        if (!$projectRepository instanceof ProjectRepository) {
            $this->fail('Fail to load ProjectRepository.');
        }
        $project = $projectRepository->findOneBy(['published' => null]);
        if (!$project instanceof Project) {
            $this->fail('Fail get unpublished Project from database.');
        }

        $client->request('GET', '/v2/projects/'.$project->getSlug().self::API_KEY);
        $this->assertResponseStatusCodeSame(404);
        $this->assertResponseFormatSame('json');
    }

    public function testProject(): void
    {
        $client = static::createClient();

        $projectRepository = static::getContainer()->get(ProjectRepository::class);
        if (!$projectRepository instanceof ProjectRepository) {
            $this->fail('Fail to load ProjectRepository.');
        }
        $projects = $projectRepository->findAllPublished();
        if (!is_array($projects) || empty($projects[0])) {
            $this->fail('Fail get published Projects from database.');
        }

        $client->request('GET', '/v2/projects/'.$projects[0]->getSlug().self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        $project = (object) $content;
        $this->assertObjectMatchClass($project, Project::class, ['project', 'project_details', 'technology', 'media']);

        // Check that the project is published
        try {
            $published = new \DateTime($project->published);
        } catch (\Exception $e) {
            $this->fail('Invalid published date.');
        }
        $this->assertLessThan(time(), $published->getTimestamp());
    }
}

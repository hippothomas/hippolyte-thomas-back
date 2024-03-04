<?php

namespace App\Tests\Controller\API;

use App\Entity\AboutMe;
use App\Tests\ApiTestCase;

class AboutMeControllerTest extends ApiTestCase
{
    public const string API_KEY = '?api_key=00000000-0000-0000-0000-000000000000';

    public function testAboutMe(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/about-me'.self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        foreach ($content as $element) {
            $this->assertIsObject($element);
            $this->assertObjectMatchClass($element, AboutMe::class);
        }
    }
}

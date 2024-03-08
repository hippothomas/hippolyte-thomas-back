<?php

namespace App\Tests\Controller\API;

use App\Entity\Social;
use App\Tests\ApiTestCase;

class SocialControllerTest extends ApiTestCase
{
    public function testSocials(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/socials'.self::API_KEY);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $response = $client->getResponse()->getContent();
        $this->assertNotFalse($response);

        $content = (array) json_decode($response);
        $this->assertNotEmpty($content);

        foreach ($content as $element) {
            $this->assertIsObject($element);
            $this->assertObjectMatchClass($element, Social::class);
        }
    }
}

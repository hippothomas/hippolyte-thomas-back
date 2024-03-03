<?php

namespace App\Tests\Controller\API;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationTest extends WebTestCase
{
    private const array PUBLIC_ROUTES = [
        '/v2/health',
    ];

    public static function provideApiEndpoint(): array
    {
        $api_endpoints = [];

        // Retrieve all api routes with no path variables
        $container = static::getContainer()->get('router');
        $routes = $container->getRouteCollection()->all();
        foreach ($routes as $route) {
            if (str_starts_with($route->getPath(), '/v2/')
                && empty($route->compile()->getPathVariables())
                && !in_array($route->getPath(), self::PUBLIC_ROUTES)) {
                $api_endpoints[] = [$route->getPath()];
            }
        }
        self::ensureKernelShutdown();

        return $api_endpoints;
    }

    #[DataProvider('provideApiEndpoint')]
    public function testWithoutApiKey(string $endpoint): void
    {
        $client = static::createClient();
        $client->request('GET', $endpoint);
        $this->assertResponseStatusCodeSame(401);
    }

    #[DataProvider('provideApiEndpoint')]
    public function testWithEmptyApiKey(string $endpoint): void
    {
        $client = static::createClient();
        $client->request('GET', $endpoint.'?api_key=');
        $this->assertResponseStatusCodeSame(401);
    }

    #[DataProvider('provideApiEndpoint')]
    public function testWithInvalidApiKey(string $endpoint): void
    {
        $client = static::createClient();
        $client->request('GET', $endpoint.'?api_key=0000');
        $this->assertResponseStatusCodeSame(401);
    }

    #[DataProvider('provideApiEndpoint')]
    public function testWithValidUuidApiKey(string $endpoint): void
    {
        $client = static::createClient();
        $client->request('GET', $endpoint.'?api_key=87949f3f-cb7e-49f5-a044-6eb2a1191147');
        $this->assertResponseStatusCodeSame(401);
    }

    #[DataProvider('provideApiEndpoint')]
    public function testWithExistingApiKey(string $endpoint): void
    {
        $client = static::createClient();
        $client->request('GET', $endpoint.'?api_key=00000000-0000-0000-0000-000000000000');
        $this->assertResponseIsSuccessful();
    }
}

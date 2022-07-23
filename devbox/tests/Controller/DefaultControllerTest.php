<?php

declare(strict_types=1);

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testCountEndpoint(): void
    {
        $client = static::createClient();

        $client->request('GET', '/count');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/count?serviceNames[]=USER-SERVICE');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/count?startDate=2022-01-01');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/count?endDate=2022-01-01');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/count?statusCode=400');
        $this->assertResponseIsSuccessful();
    }
}

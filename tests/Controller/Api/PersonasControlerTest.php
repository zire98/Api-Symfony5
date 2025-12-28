<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testCreatePersonaInvalidData()
    {

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/personas',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"nombre":""}'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/personas',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"nombre":"Sergio"}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

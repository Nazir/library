<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = self::createClient();

        $client->xmlHttpRequest(
            'POST',
            '/author/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"Name":"Александр Пушкин"}'
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}

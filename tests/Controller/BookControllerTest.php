<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    const NAME = 'Война и мир';

    public function testCreate()
    {
        $client = self::createClient();

        $client->xmlHttpRequest(
            'POST',
            '/book/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"Name":"' . self::NAME . '", "Lang":"ru", "Author": [ {"Name":"Лев Толстой"}, {"Name":"Назир Хуснутдинов"} ]}'
        );

        // echo PHP_EOL . $client->getResponse()->getContent();

        $this->assertTrue($client->getResponse()->isSuccessful());
        // $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSearch()
    {
        $client = self::createClient();

        $client->request(
            // $client->xmlHttpRequest(
            'POST',
            '/book/search',
            ['text' => self::NAME],
            // [],
            // [],
            // ['CONTENT_TYPE' => 'application/json'],
            // '{"Name":"' . self::NAME .'"}'
        );

        // Code monkey
        // echo PHP_EOL . $client->getResponse()->getContent();
        // dd($client->getResponse()->getContent());

        // $name = null;
        // if ($client->getResponse()->isSuccessful()) {
        //     $name = json_decode($client->getResponse()->getContent(), true);
        //     if (count($name) > 0)
        //     $name = $name[0];
        //     $name = $name['name'];
        // }
        // $this->assertEquals(self::NAME, $name);

        $this->assertTrue($client->getResponse()->isSuccessful());
        // $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEnSearchId()
    {
        $client = self::createClient();

        $client->xmlHttpRequest(
            'GET',
            '/en/book/1',
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
        // $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

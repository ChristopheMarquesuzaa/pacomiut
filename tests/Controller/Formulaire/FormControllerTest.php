<?php

namespace App\Tests\Controller\Formulaire;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormControllerTest extends WebTestCase
{
    public function testIndexForm()
    {
        $client = static::createClient();

        $client->request('GET', '/forms');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowForm()
    {
        $client = static::createClient();

        $client->request('GET', '/forms/show');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

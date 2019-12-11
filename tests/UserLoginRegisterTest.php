<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserLoginRegisterTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
    }

    public function testCreateauthor()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/register');
    }
}
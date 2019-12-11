<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeReviewTest extends WebTestCase
{
    public function testNewReviewRecipe()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new-recipe-review/{id}');
    }

}
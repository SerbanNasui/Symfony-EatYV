<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeTest extends WebTestCase
{
    public function testRecipeCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/new-recipe');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /new-recipe/");
        $crawler = $client->click($crawler->selectLink('Create!')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create!')->form(array(
            'app_recipe[title]'  => 'Test',
            'app_recipe[dateStart]'  => '12/12/2020',
            'app_recipe[dateEnd]'  => '12/12/2020',
            'app_recipe[price]'  => '3',
            'app_recipe[maxNrPersons]'  => '5',
            'app_recipe[address]'  => 'Test',
            'app_recipe[city]'  => 'Test',
            'app_recipe[country]'  => 'Test',
            'app_recipe[image]'  => 'Test.png',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'app_recipe[title]'  => 'Foo',
            'app_recipe[dateStart]'  => '12/12/2025',
            'app_recipe[dateEnd]'  => '12/12/2028',
            'app_recipe[price]'  => '6',
            'app_recipe[maxNrPersons]'  => '6',
            'app_recipe[address]'  => 'Foo',
            'app_recipe[city]'  => 'Foo',
            'app_recipe[country]'  => 'Foo',
            'app_recipe[image]'  => 'Foo.png',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
}
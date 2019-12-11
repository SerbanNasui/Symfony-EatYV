<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileTest extends WebTestCase
{
    public function testUserProfileCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/create-profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /create-profile");
        $crawler = $client->click($crawler->selectLink('Create!')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create!')->form(array(
            'app_userprofile[firstName]'  => 'Test',
            'app_userprofile[secondName]'  => 'Test',
            'app_userprofile[biography]'  => 'Test',
            'app_userprofile[profileImage]'  => 'Test.png',

            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'app_userprofile[firstName]'  => 'Foo',
            'app_userprofile[secondName]'  => 'Foo',
            'app_userprofile[biography]'  => 'Foo',
            'app_userprofile[profileImage]'  => 'Foo.png',
            
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
        $this->assertNotRegExp('/Foo', $client->getResponse()->getContent());
    }
}
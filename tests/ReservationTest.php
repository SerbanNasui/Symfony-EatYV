<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationTest extends WebTestCase
{
    public function testReservationCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/new-reservation/{id}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /new-reservation/{id}");
        $crawler = $client->click($crawler->selectLink('Create reservation!')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create reservation!')->form(array(
            'app_reservation[reservationForFirstName]'  => 'Test',
            'app_reservation[reservationForSecondName]'  => 'Test',
            'app_reservation[personsParticipate]'  => '4',  
            'app_reservation[dateTimeComing]'  => '12/12/2020',  
            'app_reservation[message]'  => 'Test',  
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Update reservation')->link());

        $form = $crawler->selectButton('Update reservation')->form(array(
            'app_reservation[reservationForFirstName]'  => 'Foo',
            'app_reservation[reservationForSecondName]'  => 'Foo',
            'app_reservation[personsParticipate]'  => '4',  
            'app_reservation[dateTimeComing]'  => '12/12/2028',  
            'app_reservation[message]'  => 'Foo', 
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
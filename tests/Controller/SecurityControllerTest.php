<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class SecurityControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    public function testloginAction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'user1';
        $form['_password'] = 'toctoc';

        $client->submit($form);
        
        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('Se déconnecter')->count());
    }
}

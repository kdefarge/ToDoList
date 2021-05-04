<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class DefautlControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    const LINK_CREATEUSER   = 'Créer un utilisateur';
    const LINK_TASKS        = 'Consulter la liste des tâches à faire';

    public function testHomepageGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testHomepageUser(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(false, $crawler->selectLink(self::LINK_CREATEUSER)->count() >= 1);
        $this->assertSame(true, $crawler->selectLink(self::LINK_TASKS)->count() >= 1);
    }

    public function testHomepageAdmin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/', [], [], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(true, $crawler->selectLink(self::LINK_CREATEUSER)->count() >= 1);
        $this->assertSame(true, $crawler->selectLink(self::LINK_TASKS)->count() >= 1);
    }
}

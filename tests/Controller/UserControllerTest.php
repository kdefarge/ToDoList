<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    public function testUserListGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testUserListUser(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseStatusCodeSame('403');
    }

    public function testUserListAdmin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users', [], [], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(true, $crawler->selectLink('Edit')->count() == 4);
    }

    public function testUserCreateGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/create');
        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testUserCreateUser(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/create', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseStatusCodeSame('403');
    }

    public function testUserCreateAdmin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/create', [], [], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();

        $newuserUsename = 'usertest';
        $newuserEmail = 'usertest@example.org';

        $form['user[username]']         = $newuserUsename;
        $form['user[password][first]']  = 'toctoc';
        $form['user[password][second]'] = 'toctoc';
        $form['user[email]']            = $newuserEmail;
        $form['user[roles]']            = 0;

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        $lastTr = $crawler->filter('tr')->last();
        $tds = $lastTr->filter('td');

        $this->assertSame($newuserUsename, $tds->first()->text());
        $this->assertSame($newuserEmail, $tds->getNode(1)->textContent);
        $this->assertSame('ROLE_USER', $tds->getNode(2)->textContent);

        //keep id for test 404 error after  
        $userIdNotExist = (int)$lastTr->filter('th')->html() + 1;

        $crawler = $client->click($crawler->selectLink('Edit')->last()->link());

        $form = $crawler->selectButton('Modifier')->form();

        $newuserUsename = 'usertestUpdate';
        $newuserEmail = 'usertestUpdate@example.org';

        $form['user[username]']         = $newuserUsename;
        $form['user[password][first]']  = 'toctoc';
        $form['user[password][second]'] = 'toctoc';
        $form['user[email]']            = $newuserEmail;
        $form['user[roles]']            = 1;

        $client->submit($form);
        
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        $lastTr = $crawler->filter('tr')->last();
        $tds = $lastTr->filter('td');

        $this->assertSame($newuserUsename, $tds->first()->text());
        $this->assertSame($newuserEmail, $tds->getNode(1)->textContent);
        $this->assertSame('ROLE_ADMIN', $tds->getNode(2)->textContent);

        $client->request('GET', '/users/' . $userIdNotExist . '/edit');
        $this->assertResponseStatusCodeSame('404');
    }
}

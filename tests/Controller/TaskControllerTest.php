<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class TaskControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    public function testTaskListGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testTaskListUser(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(true, $crawler->filter('div.panel')->count() == 40);
    }

    public function testTaskCrudAndDone(): void
    {
        $client = static::createClient();

        $currentUsername = 'user2';

        /**
         * CREATE AND READ
         */
        $crawler = $client->request('GET', '/tasks/create', [], [], [
            'PHP_AUTH_USER' => $currentUsername,
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();

        $taskTitle = 'title \0/';
        $taskContent = 'content :)';

        $form['task[title]'] = $taskTitle;
        $form['task[content]'] = $taskContent;

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        $lastPanel = $crawler->filter('div.panel')->last();

        $this->assertSame($taskTitle, $lastPanel->filter('h4.panel-title a')->text());
        $this->assertSame($taskContent, $lastPanel->filter('div.panel-body p')->text());
        $this->assertSame($currentUsername, $lastPanel->filter('span.task-author')->text());

        /**
         *  DONE & READ
         */
        $form = $lastPanel->selectButton('Marquer comme faite')->form();

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertSame(1, $crawler->filter('div.panel')->last()->selectButton('Marquer non terminée')->count());

        /**
         *  UPDATE AND READ
         */
        $crawler = $client->click($lastPanel->selectLink($taskTitle)->last()->link());

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();

        $taskTitle = 'title \0/ update';
        $taskContent = 'content :) update';

        $form['task[title]'] = $taskTitle;
        $form['task[content]'] = $taskContent;

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        $lastPanel = $crawler->filter('div.panel')->last();

        $this->assertSame($taskTitle, $lastPanel->filter('h4.panel-title a')->text());
        $this->assertSame($taskContent, $lastPanel->filter('div.panel-body p')->text());
        $this->assertSame($currentUsername, $lastPanel->filter('span.task-author')->text());

        /**
         *  DELETE AND READ
         */
        $form = $lastPanel->selectButton('Supprimer')->form();

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertSame(40, $crawler->filter('div.panel')->count());
    }

    public function testTaskDeleteAnonymousAsAdmin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks', [], [], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $panels = $crawler->filter('div.panel');
        $this->assertSame(40, $panels->count());
        
        //We know the first task is anonymous (fixtures/user.yaml)
        $form = $panels->first()->selectButton('Supprimer')->form();

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertSame(39, $crawler->filter('div.panel')->count());
    }

    public function testTaskDeleteAnonymousAsNotAdmin(): void
    {
        $client = static::createClient();

        //We know the first task is anonymous (fixtures/user.yaml)
        $client->request('POST', '/tasks/1/delete', [
            '_method' => 'DELETE'
        ], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $this->assertResponseStatusCodeSame('403');
    }

    public function testTaskDeleteAsNotAuthor(): void
    {
        /**
         * We know the 20 last task is not admin author and not anonymous (fixtures/task.yaml)
         * In this version admin can't delete other task except is anonymous
         */
        $client = static::createClient();

        // Delete Anonymous task for check the correct request
        $crawler = $client->request('POST', '/tasks/1/delete', [
            '_method' => 'DELETE'
        ], [], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'toctoc',
        ]);

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertSame(39, $crawler->filter('div.panel')->count());

        // Try delete task with another author
        $client->request('POST', '/tasks/21/delete', [
            '_method' => 'DELETE'
        ]);

        $this->assertResponseStatusCodeSame('403');
    }
}

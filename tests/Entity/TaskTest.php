<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testEntityTaskCreatedAt(): void
    {
        $task = new task();
        
        $createAt = new \Datetime();
        $this->assertNotSame($createAt, $task->getCreatedAt());
        $task->setCreatedAt($createAt);
        $this->assertSame($createAt, $task->getCreatedAt());
    }

    public function testEntityTaskTitle(): void
    {
        $task = new task();

        $title = 'title';
        $this->assertNotSame($title, $task->getTitle());
        $task->setTitle($title);
        $this->assertSame($title, $task->getTitle());
    }

    public function testEntityTaskContent(): void
    {
        $task = new task();

        $content = 'content';
        $this->assertNotSame($content, $task->getContent());
        $task->setContent($content);
        $this->assertSame($content, $task->getContent());
    }

    public function testEntityTaskIsDone(): void
    {
        $task = new task();

        $this->assertNotTrue($task->getIsDone());
        $this->assertNotTrue($task->isDone());
        $task->setIsDone(true);
        $this->assertTrue($task->getIsDone());
        $this->assertTrue($task->isDone());
        $task->setIsDone(false);
        $this->assertNotTrue($task->getIsDone());
        $this->assertNotTrue($task->isDone());
        $task->toggle(true);
        $this->assertTrue($task->getIsDone());
        $this->assertTrue($task->isDone());
        $task->toggle(false);
    }

    public function testEntityTaskAuthor(): void
    {
        $task = new task();

        $author = new user();
        $this->assertTrue($task->isAnonymous());
        $this->assertNotSame($author, $task->getAuthor());
        $task->setAuthor($author);
        $this->assertSame($author, $task->getAuthor());
        $this->assertNotTrue($task->isAnonymous());
        $task->setAuthor(null);
        $this->assertTrue($task->isAnonymous());
        $this->assertNotSame($author, $task->getAuthor());
    }
}

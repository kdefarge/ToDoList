<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testEntityUser(): void
    {
        $user = new User();

        $username = 'testname';
        $this->assertNotSame($username, $user->getUsername());
        $user->setUsername($username);
        $this->assertSame($username, $user->getUsername());

        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
        $this->assertTrue(in_array('ROLE_ADMIN', $user->getRoles()));

        $userpassword = 'password';
        $this->assertNotSame($userpassword, $user->getPassword());
        $user->setPassword($userpassword);
        $this->assertSame($userpassword, $user->getPassword());

        $useremail = 'email@example.com';
        $this->assertNotSame($useremail, $user->getEmail());
        $user->setEmail($useremail);
        $this->assertSame($useremail, $user->getEmail());

        $task = new Task();
        $this->assertSame(0, $user->getTasks()->count());
        $user->addTask($task);
        $this->assertSame(1, $user->getTasks()->count());
        $user->addTask($task);
        $this->assertSame(1, $user->getTasks()->count());
        $this->assertTrue($user->getTasks()->contains($task));
        $user->removeTask($task);
        $this->assertSame(0, $user->getTasks()->count());
    }
}

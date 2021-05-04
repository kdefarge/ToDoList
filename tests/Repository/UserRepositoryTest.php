<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepositoryTest extends KernelTestCase
{
    use ReloadDatabaseTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUserUpgradePassword()
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager
            ->getRepository(User::class);

        $user = $repository->findOneBy(['username' => 'user1']);

        $repository->upgradePassword($user, 'encodedtoctoc');

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user1']);

        $this->assertSame('encodedtoctoc', $user->getPassword());
    }

    public function testUserUpgradePasswordException()
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager
            ->getRepository(User::class);

        $user = $repository->findOneBy(['username' => 'user1']);
        
        /** @var UserInterface $userInterface */
        $userInterface = $this->createMock(UserInterface::class);

        $this->expectException(UnsupportedUserException::class);
        
        $repository->upgradePassword($userInterface, '');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}

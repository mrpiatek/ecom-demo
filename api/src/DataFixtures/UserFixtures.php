<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            $this->createUser('admin', 'admin', true)
        );

        $manager->persist(
            $this->createUser('user1', 'user1')
        );

        $manager->persist(
            $this->createUser('user2', 'user2')
        );

        $manager->flush();
    }

    private function createUser(string $username, string $password, bool $isAdmin = false): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $password
            )
        );

        if($isAdmin){
            $user->setRoles(['ROLE_ADMIN']);
        }

        return $user;
    }
}

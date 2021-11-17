<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername('user' . $i);
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword(
                    $user,
                    'qwerty' . $i . $i
                )
            );
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($user);
        }
        $manager->flush();
    }

}


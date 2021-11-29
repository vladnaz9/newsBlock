<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private RoleRepository $roleRepository;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, RoleRepository $roleRepository)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->roleRepository = $roleRepository;
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
            $user->addRoles($this->roleRepository->findOneBy(['name' => 'ROLE_USER']));
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class
        ];
    }
}


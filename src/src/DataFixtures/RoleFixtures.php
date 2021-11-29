<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setName('ROLE_ADMIN');
        $manager->persist($role);
        $role->setName('ROLE_USER');
        $manager->persist($role);


        $manager->flush();
    }
}

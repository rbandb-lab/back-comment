<?php

declare(strict_types=1);

namespace Infra\Symfony6\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Infra\Symfony6\ORM\Doctrine\Entity\User;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en');

        for ($u = 0; $u < 6; $u++) {
            $user = new User(
                id: Uuid::fromString($faker->uuid()),
                username: $faker->userName
            );
            $manager->persist($user);
            $this->addReference('user-'.$u, $user);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    public const USER_REFERENCE = 'user-reference';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 30; $i++) {
            $user = new User();
            if ($i == 0) {
                $user->setRoles(['ROLE_COP']);
            }
            if ($i > 0 && $i < 6) {
                $user->setRoles(['ROLE_PROFESSOR']);
            }
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setPicture($faker->imageUrl());
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setUsername($faker->userName());
            $user->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE . $faker->numberBetween(0, 2)));
            $user->setPromo($this->getReference(PromoFixtures::PROMO_REFERENCE . $faker->numberBetween(0, 4)));

            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE . $i, $user);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class,
            PromoFixtures::class,
        ];
    }
}

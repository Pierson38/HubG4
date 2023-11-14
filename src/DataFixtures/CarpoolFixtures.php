<?php

namespace App\DataFixtures;

use App\Entity\Carpool;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CarpoolFixtures extends Fixture implements DependentFixtureInterface
{
    const CARPOOL_REFERENCE = 'carpool-reference';
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i < 20; $i++) { 
            $carpool = new Carpool();
            $carpool->setFromLocation($faker->address);
            $carpool->setToLocation($faker->address);
            $carpool->setDate(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $carpool->setPlaces($faker->numberBetween(1, 5));
            $carpool->setPrice($faker->numberBetween(1, 100));
            $carpool->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, 29)));

            $manager->persist($carpool);
            $this->addReference(self::CARPOOL_REFERENCE . $i, $carpool);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}

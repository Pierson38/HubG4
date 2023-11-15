<?php

namespace App\DataFixtures;

use App\Entity\Events;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 50; $i++) { 
            $date = $faker->dateTimeBetween('now', '+1 year');
            $event = new Events();
            
            $event->setName("Event $i");
            $event->setDescription($faker->text(200));
            $event->setLocation($faker->city());
            $event->setStartAt(new \DateTimeImmutable($date->format('Y-m-d H:i:s')));
            $event->setEndAt(new \DateTimeImmutable($date->modify('+2 hours')->format('Y-m-d H:i:s')));
            $event->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . 0));
            $event->setType($faker->randomElement(['bde', 'cop']));

            $manager->persist($event);
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

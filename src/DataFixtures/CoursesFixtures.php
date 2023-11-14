<?php

namespace App\DataFixtures;

use App\Entity\Courses;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CoursesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 50; $i++) { 
            $date = $faker->dateTimeBetween('now', '+1 year');
            $courses = new Courses();
            $courses->setTitle("Courses $i");
            $courses->setStartAt(new \DateTimeImmutable($date->format('Y-m-d H:i:s')));
            $courses->setEndAt(new \DateTimeImmutable($date->modify('+2 hours')->format('Y-m-d H:i:s')));
            $courses->setProfessor($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(1, 5)));
            $courses->setClassroom($faker->city());
            $courses->setTags($faker->words(3, false));
            $courses->setDescription($faker->text(200));
            $courses->setPromo($this->getReference(PromoFixtures::PROMO_REFERENCE . $faker->numberBetween(0, 4)));
            $courses->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . 0));

            $manager->persist($courses);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PromoFixtures::class,
        ];
    }
}

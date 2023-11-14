<?php

namespace App\DataFixtures;

use App\Entity\PostsReport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostsReportsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i < 20; $i++) { 
            $report = new PostsReport();
            $report->setPost($this->getReference(PostsFixtures::POSTS_REFERENCE . rand(0, 49)));
            $report->setReportedBy($this->getReference(UserFixtures::USER_REFERENCE . rand(0, 29)));
            $report->setCategory($faker->randomElement(['Spam', 'Inappropriate', 'Other']));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PostsFixtures::class,
        ];
    }
}

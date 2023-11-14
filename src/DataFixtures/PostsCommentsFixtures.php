<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use App\Entity\PostsComments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostsCommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i < 100; $i++) { 
            $postComment = new PostsComments();
            $postComment->setContent($faker->text(200));
            $postComment->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $postComment->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $postComment->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, 29)));
            $postComment->setPost($this->getReference(PostsFixtures::POSTS_REFERENCE . $faker->numberBetween(0, 49)));
            $manager->persist($postComment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PostsFixtures::class,
            UserFixtures::class,
        ];
    }
}

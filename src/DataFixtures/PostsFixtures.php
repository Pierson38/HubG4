<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    const POSTS_REFERENCE = 'posts-reference';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 50; $i++) { 
            $post = new Posts();
            $post->setTitle("Post $i");
            $post->setContent($faker->text(200));
            $post->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, 29)));
            $post->setCategory($this->getReference(PostsCategoriesFixtures::POSTS_CATEGORIES_REFERENCE . $faker->numberBetween(0, 19)));
            $post->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $post->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));

            $manager->persist($post);
            $this->addReference(self::POSTS_REFERENCE . $i, $post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PostsCategoriesFixtures::class,
        ];
    }
}

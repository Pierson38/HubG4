<?php

namespace App\DataFixtures;

use App\Entity\PostsImages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostsImagesFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 10; $i++) { 
            $postImage = new PostsImages();
            $postImage->setPost($this->getReference(PostsFixtures::POSTS_REFERENCE . rand(0, 49)));
            $postImage->setName($faker->imageUrl(640, 480, 'cats'));
            $manager->persist($postImage);
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

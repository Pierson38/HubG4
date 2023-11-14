<?php

namespace App\DataFixtures;

use App\Entity\LbcPictures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LbcPicturesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i < 10; $i++) { 
            $lbcPicture = new LbcPictures();
            $lbcPicture->setLbc($this->getReference(LbcFixtures::LBC_REFERENCE . rand(0, 19)));
            $lbcPicture->setName($faker->imageUrl(640, 480, 'cats'));
            $manager->persist($lbcPicture);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LbcFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Folder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FolderFixtures extends Fixture implements DependentFixtureInterface
{
    const FOLDER_REFERENCE = 'folder-reference';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 10; $i++) { 
            $folder = new Folder();
            $folder->setName("Folder $i");
            $folder->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, 29)));
            $folder->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $folder->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));

            $manager->persist($folder);

            $this->addReference(self::FOLDER_REFERENCE . $i, $folder);
        }

        for ($i=10; $i < 40; $i++) { 
            $folder = new Folder();
            $folder->setName("Folder $i");
            $folder->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, 29)));
            $folder->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $folder->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
            $folder->setParent($this->getReference(self::FOLDER_REFERENCE . $faker->numberBetween(0, 9)));
            $manager->persist($folder);

            $this->addReference(self::FOLDER_REFERENCE . $i, $folder);
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

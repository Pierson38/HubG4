<?php

namespace App\DataFixtures;

use App\Entity\Permissions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PermissionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i < 50; $i++) { 
            $permission = new Permissions();
            $permission->setUser($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, 29)));
            $permission->setFolder($this->getReference(FolderFixtures::FOLDER_REFERENCE . $faker->numberBetween(0, 39)));
            $permission->setIsDeletable($faker->boolean());
            $permission->setIsEditable($faker->boolean());
            $permission->setIsReadable($faker->boolean());

            $manager->persist($permission);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            FolderFixtures::class,
        ];
    }
}

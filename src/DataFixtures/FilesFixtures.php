<?php

namespace App\DataFixtures;

use App\Entity\Files;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FilesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 100; $i++) { 
            $file = new Files();
            $file->setName("File $i");
            $file->setWeight(rand(0, 1000));
            $file->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . rand(0, 29)));
            $file->setFolder($this->getReference(FolderFixtures::FOLDER_REFERENCE . rand(0, 39)));
            $manager->persist($file);


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

<?php

namespace App\DataFixtures;

use App\Entity\Lbc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class LbcFixtures extends Fixture implements DependentFixtureInterface
{
    const LBC_REFERENCE = 'lbc-reference';
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 20; $i++) { 
            $lbc = new Lbc();
            $lbc->setTitle("Title $i");
            $lbc->setDescription("Description $i");
            $lbc->setPrice(rand(0, 1000));
            $lbc->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . rand(0, 29)));

            $manager->persist($lbc);
            $this->addReference(self::LBC_REFERENCE . $i, $lbc);
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

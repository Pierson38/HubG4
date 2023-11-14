<?php

namespace App\DataFixtures;

use App\Entity\CarpoolMembers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarpoolMembersFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 20; $i++) { 
            $carpoolMember = new CarpoolMembers();
            $carpoolMember->setCarpool($this->getReference(CarpoolFixtures::CARPOOL_REFERENCE . rand(0, 19)));
            $carpoolMember->setUser($this->getReference(UserFixtures::USER_REFERENCE . rand(0, 29)));
            $carpoolMember->setIsAccepted(rand(0, 1));
            $manager->persist($carpoolMember);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CarpoolFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PromoFixtures extends Fixture
{
    public const PROMO_REFERENCE = 'promo-reference';

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 5; $i++) {
            $promo = new Promo();
            $promo->setName("promo $i");

            $manager->persist($promo);

            $this->addReference(self::PROMO_REFERENCE . $i, $promo);

        }

        $manager->flush();
    }
}

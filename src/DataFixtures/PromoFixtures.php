<?php

namespace App\DataFixtures;

use App\Entity\Promo;
use App\Service\FileService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PromoFixtures extends Fixture
{
    function __construct(private FileService $fileService) {
        
    }

    public const PROMO_REFERENCE = 'promo-reference';

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 5; $i++) {
            $promo = new Promo();
            $promo->setName("promo $i");

            $manager->persist($promo);
            $manager->flush();

            $this->addReference(self::PROMO_REFERENCE . $i, $promo);
            $folder = $this->fileService->createPromoRepository($promo);
            $promo->setFolder($folder);
            $manager->persist($promo);
            $manager->flush();
        }

        
    }
}

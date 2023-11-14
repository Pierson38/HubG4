<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{

	public const CAMPUS_REFERENCE = 'campus-reference';



	public function load(ObjectManager $manager): void
	{
		for ($i=0; $i < 3; $i++) {
			$campus = new Campus();
			$campus->setName("Campus $i");

			$manager->persist($campus);

		    $this->addReference(self::CAMPUS_REFERENCE . $i, $campus);

		}

		$manager->flush();
	}
}

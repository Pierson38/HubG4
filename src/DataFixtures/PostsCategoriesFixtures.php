<?php

namespace App\DataFixtures;

use App\Entity\PostsCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostsCategoriesFixtures extends Fixture
{

	const POSTS_CATEGORIES_REFERENCE = 'posts-categories-reference';

	public function load(ObjectManager $manager): void
	{
		for ($i=0; $i < 6; $i++) { 
			$postCategory = new PostsCategories();
			$postCategory->setName("Category $i");
			$manager->persist($postCategory);
			$this->addReference(self::POSTS_CATEGORIES_REFERENCE . $i, $postCategory);
		}

		$manager->flush();

		for ($i=6; $i < 20; $i++) { 
			$postCategory = new PostsCategories();
			$postCategory->setName("Category $i");
			$postCategory->setCategoryParent($this->getReference(self::POSTS_CATEGORIES_REFERENCE . rand(0, 5)));
			$manager->persist($postCategory);
			$this->addReference(self::POSTS_CATEGORIES_REFERENCE . $i, $postCategory);
		}
		$manager->flush();


	}
}

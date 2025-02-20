<?php

namespace App\DataFixtures;

use App\Entity\JobCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Commercial',
        'Retail sales',
        'Creative',
        'Technology',
        'Marketing & PR',
        'Fashion & luxury',
        'Management & HR'
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        foreach (self::CATEGORIES as $categoryName) {
            $category = new JobCategory();
            $category->setName($categoryName);
            
            // Générer le slug
            $slug = $slugger->slug(strtolower($categoryName));
            $category->setSlug($slug);

            $manager->persist($category);
            
            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('category_' . strtolower(str_replace([' ', '&'], '_', $categoryName)), $category);
        }

        $manager->flush();
    }
}
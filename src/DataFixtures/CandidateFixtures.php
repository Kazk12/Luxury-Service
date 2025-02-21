<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Experience;
use App\Entity\Gender;
use App\Entity\JobCategory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CandidateFixtures extends Fixture implements DependentFixtureInterface
{
    public const CANDIDATES_DATA = [
        'admin' => [
            'firstName' => 'Admin',
            'lastName' => 'Admin',
            'adress' => '1 Admin Street',
            'currentLocation' => 'Admin City',
            'country' => 'Admin Country',
            'nationality' => 'Admin Nationality',
            'birthDate' => '1980-01-01',
            'birthPlace' => 'Admin Birthplace',
            'shortDescription' => 'Administrator of the system.',
            'profilPicture' => 'admin.jpg',
            'passport' => 'admin_passport.jpg',
            'cv' => 'admin_cv.pdf',
            'experience' => '10+ years'
        ],
        'test' => [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'adress' => '123 Avenue des Champs-Élysées',
            'currentLocation' => 'Paris',
            'country' => 'France',
            'nationality' => 'French',
            'birthDate' => '1990-05-15',
            'birthPlace' => 'Lyon',
            'shortDescription' => 'Experienced professional with a strong background in luxury retail.',
            'profilPicture' => 'john_doe.jpg',
            'passport' => 'john_doe_passport.jpg',
            'cv' => 'john_doe_cv.pdf',
            'experience' => '5+ years'
        ],
        'test2' => [
            'firstName' => 'Emma',
            'lastName' => 'Smith',
            'adress' => '45 Oxford Street',
            'currentLocation' => 'London',
            'country' => 'United Kingdom',
            'nationality' => 'British',
            'birthDate' => '1988-12-03',
            'birthPlace' => 'Manchester',
            'shortDescription' => 'Fashion industry expert with 10+ years experience.',
            'profilPicture' => 'emma_smith.jpg',
            'passport' => 'emma_smith_passport.jpg',
            'cv' => 'emma_smith_cv.pdf',
            'experience' => '10+ years'
        ],
        'test3' => [
            'firstName' => 'Marco',
            'lastName' => 'Rossi',
            'adress' => 'Via Montenapoleone 12',
            'currentLocation' => 'Milan',
            'country' => 'Italy',
            'nationality' => 'Italian',
            'birthDate' => '1992-07-21',
            'birthPlace' => 'Rome',
            'shortDescription' => 'Creative director with expertise in luxury brands.',
            'profilPicture' => 'marco_rossi.jpg',
            'passport' => 'marco_rossi_passport.jpg',
            'cv' => 'marco_rossi_cv.pdf',
            'experience' => '2+ years'
        ],
        'test4' => [
            'firstName' => 'Sophie',
            'lastName' => 'Martin',
            'adress' => 'Rue du Rhône 23',
            'currentLocation' => 'Geneva',
            'country' => 'Switzerland',
            'nationality' => 'Swiss',
            'birthDate' => '1991-09-28',
            'birthPlace' => 'Zurich',
            'shortDescription' => 'Specialized in luxury timepiece industry.',
            'profilPicture' => 'sophie_martin.jpg',
            'passport' => 'sophie_martin_passport.jpg',
            'cv' => 'sophie_martin_cv.pdf',
            'experience' => '5+ years'
        ],
        'test5' => [
            'firstName' => 'Hans',
            'lastName' => 'Weber',
            'adress' => 'Maximilianstraße 17',
            'currentLocation' => 'Munich',
            'country' => 'Germany',
            'nationality' => 'German',
            'birthDate' => '1987-03-14',
            'birthPlace' => 'Berlin',
            'shortDescription' => 'Expert in luxury automotive sector.',
            'profilPicture' => 'hans_weber.jpg',
            'passport' => 'hans_weber_passport.jpg',
            'cv' => 'hans_weber_cv.pdf',
            'experience' => '10+ years'
        ],
        'test6' => [
            'firstName' => 'Maria',
            'lastName' => 'Garcia',
            'adress' => 'Calle de Serrano 45',
            'currentLocation' => 'Madrid',
            'country' => 'Spain',
            'nationality' => 'Spanish',
            'birthDate' => '1993-11-05',
            'birthPlace' => 'Barcelona',
            'shortDescription' => 'Luxury hospitality professional.',
            'profilPicture' => 'maria_garcia.jpg',
            'passport' => 'maria_garcia_passport.jpg',
            'cv' => 'maria_garcia_cv.pdf',
            'experience' => '3+ years'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CANDIDATES_DATA as $key => $candidateData) {
            $candidate = new Candidate();
            
            // Récupérer l'utilisateur correspondant
            $user = $this->getReference('user_' . $key, User::class);
            $candidate->setUser($user);
            
            // Données de base
            $candidate->setFirstName($candidateData['firstName']);
            $candidate->setLastName($candidateData['lastName']);
            $candidate->setAdress($candidateData['adress']);
            $candidate->setCurrentLocation($candidateData['currentLocation']);
            $candidate->setCountry($candidateData['country']);
            $candidate->setNationality($candidateData['nationality']);
            $candidate->setDateBirth(new DateTimeImmutable($candidateData['birthDate']));
            $candidate->setBirthPlace($candidateData['birthPlace']);
            $candidate->setShortDescription($candidateData['shortDescription']);
            $candidate->setProfilPicture($candidateData['profilPicture']);
            $candidate->setPassport($candidateData['passport']);
            $candidate->setCv($candidateData['cv']);

            // Références aux autres entités
            try {
                $gender = $this->getReference('gender_' . strtolower(random_array_element(['male', 'female', 'other'])), Gender::class);
                $candidate->setGender($gender);
            } catch (\Exception $e) {
                // Gérer le cas où la référence n'existe pas
            }

            try {
                $jobCategory = $this->getReference('category_' . strtolower(random_array_element(['commercial', 'retail_sales', 'creative', 'technology', 'marketing_pr', 'fashion_luxury', 'management_hr'])), JobCategory::class);
                $candidate->setJobCategory($jobCategory);
            } catch (\Exception $e) {
                // Gérer le cas où la référence n'existe pas
            }

            try {
                $experience = $this->getReference('experience_' . strtolower(str_replace([' ', '+'], '_', $candidateData['experience'])), Experience::class);
                $candidate->setExperience($experience);
            } catch (\Exception $e) {
                // Gérer le cas où la référence n'existe pas
            }

            $manager->persist($candidate);
            
            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('candidate_' . $key, $candidate);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            GenderFixtures::class,
            CategoryFixtures::class,
            ExperienceFixtures::class,
        ];
    }
}

function random_array_element(array $array) {
    return $array[array_rand($array)];
}
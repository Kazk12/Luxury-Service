<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Candidate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    public const CLIENTS_DATA = [
        'recruiter' => [
            'companyName' => 'Luxury Tech Solutions',
            'activityType' => 'Technology Recruitment',
            'contactName' => 'John Doe',
            'workstation' => 'Senior Recruiter',
            'contactNumber' => '+33 6 12 34 56 78',
            'contactEmail' => 'john.doe@luxurytech.com',
            'notes' => 'Specialized in tech recruitment for luxury brands'
        ],
        'recruiter2' => [
            'companyName' => 'Elite Fashion Recruitment',
            'activityType' => 'Fashion Recruitment',
            'contactName' => 'Jane Smith',
            'workstation' => 'Talent Acquisition Manager',
            'contactNumber' => '+33 6 98 76 54 32',
            'contactEmail' => 'jane.smith@elitefashion.com',
            'notes' => 'Focus on high-end fashion recruitment'
        ],
        'recruiter3' => [
            'companyName' => 'Hospitality Heroes',
            'activityType' => 'Luxury Hospitality',
            'contactName' => 'Michel Durant',
            'workstation' => 'Recruitment Director',
            'contactNumber' => '+33 7 12 34 56 78',
            'contactEmail' => 'michel.durant@hospitalityheroes.com',
            'notes' => 'Specialized in 5-star hotels and restaurants'
        ],
        'recruiter4' => [
            'companyName' => 'Luxury Retail Experts',
            'activityType' => 'Retail Recruitment',
            'contactName' => 'Sophie Martin',
            'workstation' => 'HR Manager',
            'contactNumber' => '+33 6 23 45 67 89',
            'contactEmail' => 'sophie.martin@luxuryretail.com',
            'notes' => 'Premium retail brands specialist'
        ],
        'recruiter5' => [
            'companyName' => 'Beauty & Wellness Recruitment',
            'activityType' => 'Beauty Industry',
            'contactName' => 'Emma Wilson',
            'workstation' => 'Talent Scout',
            'contactNumber' => '+33 7 89 01 23 45',
            'contactEmail' => 'emma.wilson@beautywellness.com',
            'notes' => 'Focus on luxury spa and beauty sectors'
        ],
        'recruiter6' => [
            'companyName' => 'Yacht Crew Solutions',
            'activityType' => 'Maritime Luxury',
            'contactName' => 'Pierre Dubois',
            'workstation' => 'Maritime Recruiter',
            'contactNumber' => '+33 6 34 56 78 90',
            'contactEmail' => 'pierre.dubois@yachtcrew.com',
            'notes' => 'Specialized in luxury yacht staff recruitment'
        ],
        'recruiter7' => [
            'companyName' => 'Private Aviation Talent',
            'activityType' => 'Aviation Services',
            'contactName' => 'Marie Laurent',
            'workstation' => 'Aviation Recruitment Specialist',
            'contactNumber' => '+33 7 45 67 89 01',
            'contactEmail' => 'marie.laurent@aviationtalent.com',
            'notes' => 'Private jet crew and ground staff recruitment'
        ],
        'recruiter8' => [
            'companyName' => 'Fine Dining Resources',
            'activityType' => 'Gastronomy',
            'contactName' => 'Antoine Blanc',
            'workstation' => 'Culinary Recruiter',
            'contactNumber' => '+33 6 56 78 90 12',
            'contactEmail' => 'antoine.blanc@finedining.com',
            'notes' => 'Michelin-starred restaurants staffing'
        ],
        'recruiter9' => [
            'companyName' => 'Luxury Real Estate Recruitment',
            'activityType' => 'Real Estate',
            'contactName' => 'Claire Rousseau',
            'workstation' => 'Property Recruitment Manager',
            'contactNumber' => '+33 7 67 89 01 23',
            'contactEmail' => 'claire.rousseau@luxuryrealestate.com',
            'notes' => 'High-end real estate professionals recruitment'
        ],
        'recruiter10' => [
            'companyName' => 'VIP Services Staffing',
            'activityType' => 'Personal Services',
            'contactName' => 'Lucas Bernard',
            'workstation' => 'VIP Service Director',
            'contactNumber' => '+33 6 78 90 12 34',
            'contactEmail' => 'lucas.bernard@vipservices.com',
            'notes' => 'Personal assistant and household staff recruitment'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CLIENTS_DATA as $key => $clientData) {
            $client = new Client();
            $client->setCompagyName($clientData['companyName']);
            $client->setActivityType($clientData['activityType']);
            $client->setContactName($clientData['contactName']);
            $client->setWorkstation($clientData['workstation']);
            $client->setContactNumber($clientData['contactNumber']);
            $client->setContactEmail($clientData['contactEmail']);
            $client->setNotes($clientData['notes']);

            // Récupérer l'utilisateur correspondant depuis les références
            $user = $this->getReference('user_' . $key, User::class);
            $client->setUser($user);

            $manager->persist($client);

            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('client_' . $key, $client);

            // Créer un candidat associé au client
            $candidate = new Candidate();
            $candidate->setUser($user);
            $manager->persist($candidate);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
<?php

namespace App\Repository;

use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobOffer>
 */
class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }


    //  /**
    //  * @return JobOffer[] Returns an array of JobOffer objects
    //  */
    // public function findLatestJobOffers(): array
    // {
    //     return $this->createQueryBuilder('j')
    //         ->orderBy('j.id', 'DESC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }



/**
     * @return JobOfferWithCountDTO[]
     */
    public function find10Jobs(int $limit = 10): array
    {
        return $this->createQueryBuilder('j')
            ->select('NEW App\\DTO\\JobOfferWithCountDTO(j.id, j.jobTitle, j.salary, j.createdAt, j.description, jc.name, jc.name, j.reference)')
            ->leftJoin('j.jobCategory', 'jc')
            ->orderBy('j.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return JobOfferWithCountDTO[]
     */

    public function findAllJobs(): array
    {
        return $this->createQueryBuilder('jo')
            ->select('NEW App\\DTO\\JobOfferWithCountDTO(jo.id, jo.jobTitle, jo.salary, jo.createdAt, jo.description, jc.name, jc.slug, jo.reference)')
            ->leftJoin('jo.jobCategory', 'jc')
            ->orderBy('jo.id', 'DESC')
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return JobOffer[] Returns an array of JobOffer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JobOffer
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\Revenue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Revenue>
 */
class RevenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Revenue::class);
    }

    //    /**
    //     * @return Revenue[] Returns an array of Revenue objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Revenue
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByDateRange(?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate): array
    {
        $qb = $this->createQueryBuilder('r');
    
        // Utiliser 'BETWEEN' pour filtrer les dates
        if ($startDate && $endDate) {
            $qb->where('r.date_revenue BETWEEN :startDate AND :endDate');
            $qb->setParameter('startDate', $startDate->format('Y-m-d'));
            $qb->setParameter('endDate', $endDate->format('Y-m-d'));
        } elseif ($startDate) {
            $qb->where('r.date_revenue >= :startDate');
            $qb->setParameter('startDate', $startDate->format('Y-m-d'));
        } elseif ($endDate) {
            $qb->where('r.date_revenue <= :endDate');
            $qb->setParameter('endDate', $endDate->format('Y-m-d'));
        }
    
        return $qb->getQuery()->getResult();
    }
    


}

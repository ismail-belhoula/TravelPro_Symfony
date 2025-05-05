<?php

namespace App\Repository;

use App\Entity\Deponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deponse>
 */
class DeponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deponse::class);
    }

    //    /**
    //     * @return Deponse[] Returns an array of Deponse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Deponse
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function findByDateRange(?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate): array
{
    $qb = $this->createQueryBuilder('d');

    // Utiliser 'BETWEEN' pour filtrer les dates
    if ($startDate && $endDate) {
        $qb->where('d.Date_achat BETWEEN :startDate AND :endDate');
        $qb->setParameter('startDate', $startDate->format('Y-m-d'));
        $qb->setParameter('endDate', $endDate->format('Y-m-d'));
    } elseif ($startDate) {
        $qb->where('d.Date_achat >= :startDate');
        $qb->setParameter('startDate', $startDate->format('Y-m-d'));
    } elseif ($endDate) {
        $qb->where('d.Date_achat <= :endDate');
        $qb->setParameter('endDate', $endDate->format('Y-m-d'));
    }

    return $qb->getQuery()->getResult();
}

    
    
    
    
}

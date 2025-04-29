<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    //    /**
    //     * @return Evenement[] Returns an array of Evenement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Evenement
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function getEventStatistics(): array
{
    return $this->createQueryBuilder('e')
        ->select([
            'COUNT(e.id_event) as total_events',
            'e.type',
            'AVG(DATE_DIFF(e.date_fin, e.date_debut)) as avg_duration',
            'MIN(DATE_DIFF(e.date_fin, e.date_debut)) as min_duration',
            'MAX(DATE_DIFF(e.date_fin, e.date_debut)) as max_duration'
        ])
        ->groupBy('e.type')
        ->getQuery()
        ->getResult();
}
}

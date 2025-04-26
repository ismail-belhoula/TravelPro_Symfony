<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function getEventStatistics(): array
    {
        try {
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
        } catch (\Exception $e) {
            // Log the error
            error_log("Error in getEventStatistics: " . $e->getMessage());
            return [];
        }
    }

    public function findByDateRange(\DateTime $start, \DateTime $end): array
    {
        try {
            return $this->createQueryBuilder('e')
                ->where('e.date_debut >= :start')
                ->andWhere('e.date_fin <= :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->orderBy('e.date_debut', 'ASC')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            error_log("Error in findByDateRange: " . $e->getMessage());
            return [];
        }
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            error_log("Error saving Evenement: " . $e->getMessage());
            throw $e;
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($entity);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            error_log("Error removing Evenement: " . $e->getMessage());
            throw $e;
        }
    }
}
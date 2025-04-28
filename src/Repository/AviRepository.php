<?php

namespace App\Repository;

use App\Entity\Avi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avi>
 */
class AviRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avi::class);
    }
    public function search(string $term): array
    {
        $qb = $this->createQueryBuilder('a');
        $lowerTerm = strtolower($term);
        
        // Recherche standard
        $qb->where('a.commentaire LIKE :term OR a.note LIKE :term');
        
        // Recherche par statut
        if (str_contains($lowerTerm, 'accepté') || str_contains($lowerTerm, 'accepte')) {
            $qb->orWhere('a.est_accepte = true');
        } elseif (str_contains($lowerTerm, 'attente') || str_contains($lowerTerm, 'en attente')) {
            $qb->orWhere('a.est_accepte = false');
        }
        
        // Recherche par note exacte si le terme est un nombre
        if (is_numeric($term)) {
            $qb->orWhere('a.note = :note')
               ->setParameter('note', (int)$term);
        }
        
        $qb->setParameter('term', '%'.$term.'%');
        
        return $qb->getQuery()->getResult();
    }
    public function getNoteStatistics(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.note, COUNT(a.id_avis) as count')
            ->where('a.estAccepte = true')
            ->groupBy('a.note')
            ->orderBy('a.note', 'ASC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Avi[] Returns an array of Avi objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Avi
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

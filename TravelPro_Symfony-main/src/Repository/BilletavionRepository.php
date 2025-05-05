<?php

namespace App\Repository;

use App\Entity\Billetavion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Billetavion>
 */
class BilletavionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Billetavion::class);
    }

    public function findByCriteria(?string $villeDepart, ?string $villeArrivee, ?string $dateDepart): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.villeDepart = :villeDepart')
            ->andWhere('b.villeArrivee = :villeArrivee')
            ->andWhere('b.dateDepart >= :dateDepart')
            ->setParameter('villeDepart', $villeDepart)
            ->setParameter('villeArrivee', $villeArrivee)
            ->setParameter('dateDepart', new \DateTime($dateDepart))
            ->orderBy('b.prix', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function searchByQuery(string $query): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.compagnie LIKE :query')
            ->orWhere('b.classBillet LIKE :query')
            ->orWhere('b.villeDepart LIKE :query')
            ->orWhere('b.villeArrivee LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('b.prix', 'ASC');

        // Log the query for debugging
        $sql = $qb->getQuery()->getSQL();
        $parameters = $qb->getQuery()->getParameters();
        error_log("searchByQuery SQL: $sql");
        error_log("searchByQuery Parameters: " . print_r($parameters, true));

        return $qb->getQuery()->getResult();
    }

    public function save(Billetavion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Billetavion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
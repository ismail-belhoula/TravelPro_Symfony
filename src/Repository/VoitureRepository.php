<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voiture>
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function findByCriteria(?string $dateDebut, ?string $dateFin): array
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.disponible = :disponible')
            ->andWhere('v.dateDeLocation <= :dateDebut')
            ->andWhere('v.dateDeRemise >= :dateFin')
            ->setParameter('disponible', true)
            ->setParameter('dateDebut', new \DateTime($dateDebut))
            ->setParameter('dateFin', new \DateTime($dateFin))
            ->orderBy('v.prixParJour', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Search cars by query string.
     *
     * @param string $query Search query
     * @return Voiture[] Array of Voiture entities
     */
    public function searchByQuery(string $query): array
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.marque LIKE :query')
            ->orWhere('v.modele LIKE :query')
            ->orWhere('CAST(v.annee AS string) LIKE :query')
            ->orWhere('CAST(v.prixParJour AS string) LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('v.marque', 'ASC');

        // Log the query for debugging
        $sql = $qb->getQuery()->getSQL();
        $parameters = $qb->getQuery()->getParameters();
        error_log("searchByQuery SQL: $sql");
        error_log("searchByQuery Parameters: " . print_r($parameters, true));

        return $qb->getQuery()->getResult();
    }

    public function save(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
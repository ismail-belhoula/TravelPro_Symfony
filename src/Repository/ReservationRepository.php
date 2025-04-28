<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Search reservations by query string.
     *
     * @param string $query
     * @return Reservation[]
     */
    public function searchByQuery(string $query): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.client', 'c')
            ->leftJoin('c.utilisateur', 'u')
            ->leftJoin('r.voiture', 'v')
            ->leftJoin('r.billetAvion', 'b')
            ->leftJoin('r.hotel', 'h')
            ->where('u.nomUtilisateur LIKE :query')
            ->orWhere('u.prenom LIKE :query')
            ->orWhere('v.marque LIKE :query')
            ->orWhere('b.compagnie LIKE :query')
            ->orWhere('h.nom LIKE :query')
            ->orWhere('r.statut LIKE :query')
            ->setParameter('query', '%' . $query . '%');

        // Log the query for debugging
        $sql = $qb->getQuery()->getSQL();
        $parameters = $qb->getQuery()->getParameters();
        error_log("searchByQuery SQL: $sql");
        error_log("searchByQuery Parameters: " . print_r($parameters, true));

        return $qb->getQuery()->getResult();
    }
}
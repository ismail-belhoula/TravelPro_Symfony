<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hotel>
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    public function findByCriteria(?string $ville, ?string $typeChambre): array
    {
        $qb = $this->createQueryBuilder('h')
            ->where('h.ville = :ville')
            ->andWhere('h.typeDeChambre = :typeChambre')
            ->andWhere('h.disponible = :disponible')
            ->setParameter('ville', $ville)
            ->setParameter('typeChambre', $typeChambre)
            ->setParameter('disponible', true)
            ->orderBy('h.prixParNuit', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findAvailableHotels(): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.disponible = :disponible')
            ->setParameter('disponible', true)
            ->orderBy('h.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findHotelsByVille(string $ville): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.ville = :ville')
            ->setParameter('ville', $ville)
            ->andWhere('h.disponible = :disponible')
            ->setParameter('disponible', true)
            ->orderBy('h.nombreEtoile', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(Hotel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hotel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
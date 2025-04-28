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

    /**
     * Find hotels by city, room type, and availability dates.
     *
     * @param string $ville City name
     * @param string $typeChambre Room type
     * @param \DateTime|null $dateCheckIn Check-in date
     * @param \DateTime|null $dateCheckOut Check-out date
     * @return Hotel[] Array of Hotel entities
     */
    public function findByCriteria(string $ville, string $typeChambre, ?\DateTime $dateCheckIn, ?\DateTime $dateCheckOut): array
    {
        $qb = $this->createQueryBuilder('h')
            ->where('LOWER(h.ville) = LOWER(:ville)')
            ->andWhere('h.typeDeChambre = :typeChambre')
            ->andWhere('h.disponible = :disponible')
            ->setParameter('ville', $ville)
            ->setParameter('typeChambre', $typeChambre)
            ->setParameter('disponible', true);

        // Ajouter les conditions de date uniquement si elles sont fournies
        if ($dateCheckIn) {
            $qb->andWhere('h.dateCheckIn <= :dateCheckIn')
               ->setParameter('dateCheckIn', $dateCheckIn);
        }
        if ($dateCheckOut) {
            $qb->andWhere('h.dateCheckOut >= :dateCheckOut')
               ->setParameter('dateCheckOut', $dateCheckOut);
        }

        return $qb->orderBy('h.prixParNuit', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find all available hotels.
     *
     * @return Hotel[] Array of Hotel entities
     */
    public function findAvailableHotels(): array
    {
        return $this->createQueryBuilder('h')
            ->where('h.disponible = :disponible')
            ->setParameter('disponible', true)
            ->orderBy('h.nom', 'ASC')
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
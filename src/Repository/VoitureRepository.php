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
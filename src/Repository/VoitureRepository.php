<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<Voiture>
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function findByCriteria(?string $dateDebut, ?string $dateFin): array
    {
        try {
            $qb = $this->createQueryBuilder('v')
                ->where('v.disponible = :disponible')
                ->setParameter('disponible', true);

            if ($dateDebut && $dateFin) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->isNull('v.dateDeLocation'),
                        $qb->expr()->andX(
                            'v.dateDeLocation <= :dateFin',
                            'v.dateDeRemise >= :dateDebut'
                        )
                    )
                )
                ->setParameter('dateDebut', new \DateTime($dateDebut))
                ->setParameter('dateFin', new \DateTime($dateFin));
            }

            return $qb->orderBy('v.prixParJour', 'ASC')
                     ->getQuery()
                     ->getResult();
        } catch (\Exception $e) {
            // Log the error
            error_log("Error in findByCriteria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Search cars by query string.
     *
     * @param string $query Search query
     * @return Voiture[] Array of Voiture entities
     */
    public function searchByQuery(string $query): array
    {
        try {
            $qb = $this->createQueryBuilder('v');
            $searchTerms = explode(' ', trim($query));

            foreach ($searchTerms as $key => $term) {
                $parameterName = 'query' . $key;
                $qb->orWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('v.marque', ':' . $parameterName),
                        $qb->expr()->like('v.modele', ':' . $parameterName),
                        $qb->expr()->like('CAST(v.annee AS string)', ':' . $parameterName),
                        $qb->expr()->like('CAST(v.prixParJour AS string)', ':' . $parameterName)
                    )
                )
                ->setParameter($parameterName, '%' . $term . '%');
            }

            return $qb->orderBy('v.marque', 'ASC')
                     ->getQuery()
                     ->getResult();
        } catch (\Exception $e) {
            error_log("Error in searchByQuery: " . $e->getMessage());
            return [];
        }
    }

    public function save(Voiture $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            error_log("Error saving Voiture: " . $e->getMessage());
            throw $e;
        }
    }

    public function remove(Voiture $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($entity);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (\Exception $e) {
            error_log("Error removing Voiture: " . $e->getMessage());
            throw $e;
        }
    }
}
<?php

namespace App\Repository;

use App\Entity\Maison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Maison>
 *
 * @method Maison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maison[]    findAll()
 * @method Maison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maison::class);
    }

    public function search($search, $type): ?array
    {
      $query = $this->createQueryBuilder('m');
              $query->where('MATCH_AGAINST(m.name, m.city, m.adresse) AGAINST (:mots boolean) > 0')
                  ->setParameter('mots', $search)
                  ->andWhere('m.type = :type' )
                  ->setParameter('type', $type);
          return $query->getQuery()->getResult()

        ;}
}

<?php

namespace App\Repository;

use App\Entity\HistoricQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistoricQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoricQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoricQuestion[]    findAll()
 * @method HistoricQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoricQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoricQuestion::class);
    }

    // /**
    //  * @return HistoricQuestion[] Returns an array of HistoricQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoricQuestion
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

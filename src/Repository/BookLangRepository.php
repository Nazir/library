<?php

namespace App\Repository;

use App\Entity\BookLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookLang[]    findAll()
 * @method BookLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookLang::class);
    }

    // /**
    //  * @return BookLang[] Returns an array of BookLang objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookLang
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

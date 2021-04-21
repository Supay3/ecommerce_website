<?php

namespace App\Repository\Shop\Payement;

use App\Entity\Shop\Payement\PayementMethodTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PayementMethodTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayementMethodTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayementMethodTranslation[]    findAll()
 * @method PayementMethodTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayementMethodTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayementMethodTranslation::class);
    }

    // /**
    //  * @return PayementMethodTranslation[] Returns an array of PayementMethodTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PayementMethodTranslation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

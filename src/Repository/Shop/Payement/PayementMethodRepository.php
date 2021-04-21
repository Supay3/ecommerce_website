<?php

namespace App\Repository\Shop\Payement;

use App\Entity\Shop\Payement\PayementMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PayementMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayementMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayementMethod[]    findAll()
 * @method PayementMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayementMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayementMethod::class);
    }

    // /**
    //  * @return PayementMethod[] Returns an array of PayementMethod objects
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
    public function findOneBySomeField($value): ?PayementMethod
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

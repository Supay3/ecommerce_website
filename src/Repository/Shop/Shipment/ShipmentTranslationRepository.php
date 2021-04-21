<?php

namespace App\Repository\Shop\Shipment;

use App\Entity\Shop\Shipment\ShipmentTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShipmentTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentTranslation[]    findAll()
 * @method ShipmentTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentTranslation::class);
    }

    // /**
    //  * @return ShipmentTranslation[] Returns an array of ShipmentTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShipmentTranslation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

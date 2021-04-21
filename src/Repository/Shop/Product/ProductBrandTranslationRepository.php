<?php

namespace App\Repository\Shop\Product;

use App\Entity\Shop\Product\ProductBrandTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductBrandTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductBrandTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductBrandTranslation[]    findAll()
 * @method ProductBrandTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductBrandTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductBrandTranslation::class);
    }

    // /**
    //  * @return ProductBrandTranslation[] Returns an array of ProductBrandTranslation objects
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
    public function findOneBySomeField($value): ?ProductBrandTranslation
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

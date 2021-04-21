<?php

namespace App\Repository\Shop\Product;

use App\Entity\Shop\Product\ProductBrandImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductBrandImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductBrandImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductBrandImage[]    findAll()
 * @method ProductBrandImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductBrandImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductBrandImage::class);
    }

    // /**
    //  * @return ProductBrandImage[] Returns an array of ProductBrandImage objects
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
    public function findOneBySomeField($value): ?ProductBrandImage
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

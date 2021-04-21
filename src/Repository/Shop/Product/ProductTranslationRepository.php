<?php

namespace App\Repository\Shop\Product;

use App\Entity\Shop\Product\ProductTranslation;
use App\Entity\Shop\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductTranslation[]    findAll()
 * @method ProductTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductTranslation::class);
    }

    public function findAllEnabled(string $locale, ProductSearch $productSearch)
    {
        $query = $this->createQueryBuilder('product_translation')
            ->join('product_translation.product', 'product')
            ->join('product_translation.locale', 'locale')
            ->where('product.enabled = true')
            ->andWhere('locale.code = :locale')
            ->setParameter('locale', $locale)
        ;
        if ($productSearch->getMaxPrice()) {
            $query = $query
                ->andWhere('product.price <= :price')
                ->setParameter('price', $productSearch->getMaxPrice())
            ;
        }
        if ($productSearch->getProductCategories()->count() > 0) {
            $k = 0;
            $query = $query->join('product.productType', 'productType');
            foreach ($productSearch->getProductCategories() as $category) {
                $k++;
                $query = $query
                    ->andWhere("productType.productCategory IN (:category$k)")
                    ->setParameter("category$k", $category)
                ;
            }
        }
        if ($productSearch->getProductTypes()->count() > 0) {
            $k = 0;
            foreach ($productSearch->getProductTypes() as $type) {
                $k++;
                $query = $query
                    ->andWhere("product.productType IN (:type$k)")
                    ->setParameter("type$k", $type)
                ;
            }
        }
        return $query
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return ProductTranslation[] Returns an array of ProductTranslation objects
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
    public function findOneBySomeField($value): ?ProductTranslation
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

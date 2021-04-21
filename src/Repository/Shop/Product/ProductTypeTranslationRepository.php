<?php

namespace App\Repository\Shop\Product;

use App\Entity\Shop\Product\ProductTypeTranslation;
use App\Entity\Shop\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductTypeTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductTypeTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductTypeTranslation[]    findAll()
 * @method ProductTypeTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTypeTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductTypeTranslation::class);
    }

    public function findAllByLocale(string $locale, ProductSearch $productSearch)
    {
        $query = $this->createQueryBuilder('product_type_translation')
            ->join('product_type_translation.productType', 'product_type')
            ->join('product_type.productCategory', 'product_category')
            ->join('product_type.products', 'products')
            ->join('product_type_translation.locale', 'locale')
            ->where('locale.code = :locale')
            ->andWhere('products.enabled = true')
            ->setParameter('locale', $locale)
        ;

        if ($productSearch->getProductTypes()->count() > 0) {
            $k = 0;
            foreach ($productSearch->getProductTypes() as $type) {
                $k++;
                $query = $query
                    ->andWhere("product_type.id IN (:type$k)")
                    ->setParameter("type$k", $type)
                ;
            }
        }
        if ($productSearch->getProductCategories()->count() > 0) {
            $k = 0;
            foreach ($productSearch->getProductCategories() as $category) {
                $k++;
                $query = $query
                    ->andWhere("product_category.id IN (:category$k)")
                    ->setParameter("category$k", $category)
                ;
            }
        }
        if ($productSearch->getMaxPrice()) {
            $query = $query
                ->andWhere('products.price <= :price')
                ->setParameter('price', $productSearch->getMaxPrice())
            ;
        }
        return $query
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return ProductTypeTranslation[] Returns an array of ProductTypeTranslation objects
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
    public function findOneBySomeField($value): ?ProductTypeTranslation
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

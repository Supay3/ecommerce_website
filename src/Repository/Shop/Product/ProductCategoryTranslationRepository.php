<?php

namespace App\Repository\Shop\Product;

use App\Entity\Shop\Product\ProductCategoryTranslation;
use App\Entity\Shop\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductCategoryTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategoryTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategoryTranslation[]    findAll()
 * @method ProductCategoryTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategoryTranslation::class);
    }

    public function findAllByLocale(string $locale, ProductSearch $productSearch)
    {
        $query = $this->createQueryBuilder('product_category_translation')
            ->join('product_category_translation.productCategory', 'product_category')
            ->join('product_category.productTypes', 'product_types')
            ->join('product_types.products', 'products')
            ->join('product_category_translation.locale', 'locale')
            ->where('locale.code = :locale')
            ->andWhere('products.enabled = true')
            ->setParameter('locale', $locale)
        ;

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
        if ($productSearch->getProductTypes()->count() > 0) {
            $k = 0;
            foreach ($productSearch->getProductTypes() as $type) {
                $k++;
                $query = $query
                    ->andWhere("product_types.id IN (:type$k)")
                    ->setParameter("type$k", $type)
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
    //  * @return ProductCategoryTranslation[] Returns an array of ProductCategoryTranslation objects
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
    public function findOneBySomeField($value): ?ProductCategoryTranslation
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

<?php


namespace App\Entity\Shop;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class ProductSearch
{
    #[Assert\Range(min: 5)]
    private ?int $maxPrice = null;

    private Collection $productTypes;

    private Collection $productCategories;

    public function __construct()
    {
        $this->productTypes = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int $maxPrice
     * @return ProductSearch
     */
    public function setMaxPrice(int $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductTypes(): Collection
    {
        return $this->productTypes;
    }

    /**
     * @param ArrayCollection $productTypes
     * @return ProductSearch
     */
    public function setProductTypes(ArrayCollection $productTypes): self
    {
        $this->productTypes = $productTypes;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    /**
     * @param ArrayCollection $productCategories
     * @return ProductSearch
     */
    public function setProductCategory(ArrayCollection $productCategories): self
    {
        $this->productCategories = $productCategories;
        return $this;
    }

}
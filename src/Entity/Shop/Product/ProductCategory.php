<?php

namespace App\Entity\Shop\Product;

use App\Repository\Shop\Product\ProductCategoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=ProductCategoryRepository::class)
 */
class ProductCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank,
        Assert\Length(min: 3, max: 255)
    ]
    private ?string $code = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\OneToMany(targetEntity=ProductCategoryTranslation::class, mappedBy="productCategory", orphanRemoval=true, cascade={"persist", "remove"})
     */
    #[AcmeAssert\UniqueLocale]
    private Collection $productCategoryTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductCategoryImage::class, mappedBy="productCategory", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $images;

    /**
     * @ORM\OneToMany(targetEntity=ProductType::class, mappedBy="productCategory", orphanRemoval=true)
     */
    private Collection $productTypes;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->productCategoryTranslations = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->productTypes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductCategoryTranslations(): Collection
    {
        return $this->productCategoryTranslations;
    }

    public function addProductCategoryTranslation(ProductCategoryTranslation $productCategoryTranslation): self
    {
        if (!$this->productCategoryTranslations->contains($productCategoryTranslation)) {
            $this->productCategoryTranslations[] = $productCategoryTranslation;
            $productCategoryTranslation->setProductCategory($this);
        }

        return $this;
    }

    public function removeProductCategoryTranslation(ProductCategoryTranslation $productCategoryTranslation): self
    {
        if ($this->productCategoryTranslations->removeElement($productCategoryTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productCategoryTranslation->getProductCategory() === $this) {
                $productCategoryTranslation->setProductCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ProductCategoryImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProductCategory($this);
        }

        return $this;
    }

    public function removeImage(ProductCategoryImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProductCategory() === $this) {
                $image->setProductCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductTypes(): Collection
    {
        return $this->productTypes;
    }

    public function addProductType(ProductType $productType): self
    {
        if (!$this->productTypes->contains($productType)) {
            $this->productTypes[] = $productType;
            $productType->setProductCategory($this);
        }

        return $this;
    }

    public function removeProductType(ProductType $productType): self
    {
        if ($this->productTypes->removeElement($productType)) {
            // set the owning side to null (unless already changed)
            if ($productType->getProductCategory() === $this) {
                $productType->setProductCategory(null);
            }
        }

        return $this;
    }
}

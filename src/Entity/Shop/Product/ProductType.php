<?php

namespace App\Entity\Shop\Product;

use App\Repository\Shop\Product\ProductTypeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=ProductTypeRepository::class)
 */
class ProductType
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
     * @ORM\OneToMany(targetEntity=ProductTypeTranslation::class, mappedBy="productType", orphanRemoval=true, cascade={"persist", "remove"})
     */
    #[AcmeAssert\UniqueLocale]
    private Collection $productTypeTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductTypeImage::class, mappedBy="productType", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $images;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="productTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private ?ProductCategory $productCategory = null;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="productType", orphanRemoval=true)
     */
    private Collection $products;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->productTypeTranslations = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->products = new ArrayCollection();
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
    public function getProductTypeTranslations(): Collection
    {
        return $this->productTypeTranslations;
    }

    public function addProductTypeTranslation(ProductTypeTranslation $productTypeTranslation): self
    {
        if (!$this->productTypeTranslations->contains($productTypeTranslation)) {
            $this->productTypeTranslations[] = $productTypeTranslation;
            $productTypeTranslation->setProductType($this);
        }

        return $this;
    }

    public function removeProductTypeTranslation(ProductTypeTranslation $productTypeTranslation): self
    {
        if ($this->productTypeTranslations->removeElement($productTypeTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productTypeTranslation->getProductType() === $this) {
                $productTypeTranslation->setProductType(null);
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

    public function addImage(ProductTypeImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProductType($this);
        }

        return $this;
    }

    public function removeImage(ProductTypeImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProductType() === $this) {
                $image->setProductType(null);
            }
        }

        return $this;
    }

    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    public function setProductCategory(?ProductCategory $productCategory): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProductType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductType() === $this) {
                $product->setProductType(null);
            }
        }

        return $this;
    }
}

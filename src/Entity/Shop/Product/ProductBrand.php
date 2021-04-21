<?php

namespace App\Entity\Shop\Product;

use App\Repository\Shop\Product\ProductBrandRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=ProductBrandRepository::class)
 */
class ProductBrand
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
     * @ORM\Column(type="text", nullable=true)
     */
    #[Assert\Url]
    private ?string $link = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="productBrand")
     */
    private Collection $products;

    /**
     * @ORM\OneToMany(targetEntity=ProductBrandTranslation::class, mappedBy="productBrand", orphanRemoval=true, cascade={"persist", "remove"})
     */
    #[AcmeAssert\UniqueLocale]
    private Collection $productBrandTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductBrandImage::class, mappedBy="productBrand", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $images;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->products = new ArrayCollection();
        $this->productBrandTranslations = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProductBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductBrand() === $this) {
                $product->setProductBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductBrandTranslations(): Collection
    {
        return $this->productBrandTranslations;
    }

    public function addProductBrandTranslation(ProductBrandTranslation $productBrandTranslation): self
    {
        if (!$this->productBrandTranslations->contains($productBrandTranslation)) {
            $this->productBrandTranslations[] = $productBrandTranslation;
            $productBrandTranslation->setProductBrand($this);
        }

        return $this;
    }

    public function removeProductBrandTranslation(ProductBrandTranslation $productBrandTranslation): self
    {
        if ($this->productBrandTranslations->removeElement($productBrandTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productBrandTranslation->getProductBrand() === $this) {
                $productBrandTranslation->setProductBrand(null);
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

    public function addImage(ProductBrandImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProductBrand($this);
        }

        return $this;
    }

    public function removeImage(ProductBrandImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProductBrand() === $this) {
                $image->setProductBrand(null);
            }
        }

        return $this;
    }
}

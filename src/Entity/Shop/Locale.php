<?php

namespace App\Entity\Shop;

use App\Entity\Shop\Payement\PayementMethodTranslation;
use App\Entity\Shop\Product\ProductBrandTranslation;
use App\Entity\Shop\Product\ProductCategoryTranslation;
use App\Entity\Shop\Product\ProductTranslation;
use App\Entity\Shop\Product\ProductTypeTranslation;
use App\Entity\Shop\Shipment\ShipmentTranslation;
use App\Repository\Shop\LocaleRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LocaleRepository::class)
 */
class Locale
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
        Assert\Length(min: 5, max: 5),
        Assert\Locale
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
     * @ORM\OneToMany(targetEntity=PayementMethodTranslation::class, mappedBy="locale")
     */
    private Collection $payementMethodTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductBrandTranslation::class, mappedBy="locale")
     */
    private Collection $productBrandTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductCategoryTranslation::class, mappedBy="locale")
     */
    private Collection $productCategoryTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductTranslation::class, mappedBy="locale")
     */
    private Collection $productTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ProductTypeTranslation::class, mappedBy="locale")
     */
    private Collection $productTypeTranslations;

    /**
     * @ORM\OneToMany(targetEntity=ShipmentTranslation::class, mappedBy="locale")
     */
    private Collection $shipmentTranslations;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->payementMethodTranslations = new ArrayCollection();
        $this->productBrandTranslations = new ArrayCollection();
        $this->productCategoryTranslations = new ArrayCollection();
        $this->productTranslations = new ArrayCollection();
        $this->productTypeTranslations = new ArrayCollection();
        $this->shipmentTranslations = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getCode();
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
    public function getPayementMethodTranslations(): Collection
    {
        return $this->payementMethodTranslations;
    }

    public function addPayementMethodTranslation(PayementMethodTranslation $payementMethodTranslation): self
    {
        if (!$this->payementMethodTranslations->contains($payementMethodTranslation)) {
            $this->payementMethodTranslations[] = $payementMethodTranslation;
            $payementMethodTranslation->setLocale($this);
        }

        return $this;
    }

    public function removePayementMethodTranslation(PayementMethodTranslation $payementMethodTranslation): self
    {
        if ($this->payementMethodTranslations->removeElement($payementMethodTranslation)) {
            // set the owning side to null (unless already changed)
            if ($payementMethodTranslation->getLocale() === $this) {
                $payementMethodTranslation->setLocale(null);
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
            $productBrandTranslation->setLocale($this);
        }

        return $this;
    }

    public function removeProductBrandTranslation(ProductBrandTranslation $productBrandTranslation): self
    {
        if ($this->productBrandTranslations->removeElement($productBrandTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productBrandTranslation->getLocale() === $this) {
                $productBrandTranslation->setLocale(null);
            }
        }

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
            $productCategoryTranslation->setLocale($this);
        }

        return $this;
    }

    public function removeProductCategoryTranslation(ProductCategoryTranslation $productCategoryTranslation): self
    {
        if ($this->productCategoryTranslations->removeElement($productCategoryTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productCategoryTranslation->getLocale() === $this) {
                $productCategoryTranslation->setLocale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductTranslations(): Collection
    {
        return $this->productTranslations;
    }

    public function addProductTranslation(ProductTranslation $productTranslation): self
    {
        if (!$this->productTranslations->contains($productTranslation)) {
            $this->productTranslations[] = $productTranslation;
            $productTranslation->setLocale($this);
        }

        return $this;
    }

    public function removeProductTranslation(ProductTranslation $productTranslation): self
    {
        if ($this->productTranslations->removeElement($productTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productTranslation->getLocale() === $this) {
                $productTranslation->setLocale(null);
            }
        }

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
            $productTypeTranslation->setLocale($this);
        }

        return $this;
    }

    public function removeProductTypeTranslation(ProductTypeTranslation $productTypeTranslation): self
    {
        if ($this->productTypeTranslations->removeElement($productTypeTranslation)) {
            // set the owning side to null (unless already changed)
            if ($productTypeTranslation->getLocale() === $this) {
                $productTypeTranslation->setLocale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getShipmentTranslations(): Collection
    {
        return $this->shipmentTranslations;
    }

    public function addShipmentTranslation(ShipmentTranslation $shipmentTranslation): self
    {
        if (!$this->shipmentTranslations->contains($shipmentTranslation)) {
            $this->shipmentTranslations[] = $shipmentTranslation;
            $shipmentTranslation->setLocale($this);
        }

        return $this;
    }

    public function removeShipmentTranslation(ShipmentTranslation $shipmentTranslation): self
    {
        if ($this->shipmentTranslations->removeElement($shipmentTranslation)) {
            // set the owning side to null (unless already changed)
            if ($shipmentTranslation->getLocale() === $this) {
                $shipmentTranslation->setLocale(null);
            }
        }

        return $this;
    }
}

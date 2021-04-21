<?php

namespace App\Entity\Shop\Product;

use App\Entity\Shop\Locale;
use App\Repository\Shop\Product\ProductBrandTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductBrandTranslationRepository::class)
 */
class ProductBrandTranslation
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
    private ?string $name = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Assert\Length(min: 3)]
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity=ProductBrand::class, inversedBy="productBrandTranslations")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private ?ProductBrand $productBrand = null;

    /**
     * @ORM\ManyToOne(targetEntity=Locale::class, inversedBy="productBrandTranslations")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private ?Locale $locale = null;

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProductBrand(): ?ProductBrand
    {
        return $this->productBrand;
    }

    public function setProductBrand(?ProductBrand $productBrand): self
    {
        $this->productBrand = $productBrand;

        return $this;
    }

    public function getLocale(): ?Locale
    {
        return $this->locale;
    }

    public function setLocale(?Locale $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}

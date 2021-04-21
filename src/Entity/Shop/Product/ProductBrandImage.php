<?php

namespace App\Entity\Shop\Product;

use App\Repository\Shop\Product\ProductBrandImageRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProductBrandImageRepository::class)
 * @Vich\Uploadable()
 */
class ProductBrandImage
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
    private ?string $imageName = null;

    /**
     * @Vich\UploadableField(mapping="product_brand_image", fileNameProperty="imageName")
     */
    #[Assert\Image(mimeTypes: 'images/jpeg')]
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\ManyToOne(targetEntity=ProductBrand::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private ?ProductBrand $productBrand = null;

    public function __construct()
    {
        $this->created_at = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @param File|null $imageFile
     * @return ProductBrandImage
     */
    public function setImageFile(?File $imageFile = null): ProductBrandImage
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updated_at = new DateTime();
        }
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    public function getProductBrand(): ?ProductBrand
    {
        return $this->productBrand;
    }

    public function setProductBrand(?ProductBrand $productBrand): self
    {
        $this->productBrand = $productBrand;

        return $this;
    }
}

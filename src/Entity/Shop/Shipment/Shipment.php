<?php

namespace App\Entity\Shop\Shipment;

use App\Repository\Shop\Shipment\ShipmentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=ShipmentRepository::class)
 */
class Shipment
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
     * @ORM\Column(type="float")
     */
    #[
        Assert\NotBlank,
        Assert\Positive
    ]
    private ?float $price = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\OneToMany(targetEntity=ShipmentTranslation::class, mappedBy="shipment", orphanRemoval=true, cascade={"persist", "remove"})
     */
    #[AcmeAssert\UniqueLocale]
    private Collection $shipmentTranslations;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->shipmentTranslations = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
    public function getShipmentTranslations(): Collection
    {
        return $this->shipmentTranslations;
    }

    public function addShipmentTranslation(ShipmentTranslation $shipmentTranslation): self
    {
        if (!$this->shipmentTranslations->contains($shipmentTranslation)) {
            $this->shipmentTranslations[] = $shipmentTranslation;
            $shipmentTranslation->setShipment($this);
        }

        return $this;
    }

    public function removeShipmentTranslation(ShipmentTranslation $shipmentTranslation): self
    {
        if ($this->shipmentTranslations->removeElement($shipmentTranslation)) {
            // set the owning side to null (unless already changed)
            if ($shipmentTranslation->getShipment() === $this) {
                $shipmentTranslation->setShipment(null);
            }
        }

        return $this;
    }
}

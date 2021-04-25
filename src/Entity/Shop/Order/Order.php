<?php

namespace App\Entity\Shop\Order;

use App\Entity\Shop\Shipment\Shipment;
use App\Entity\User\User;
use App\Repository\Shop\Order\OrderRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    const STATE = [
        0 => 'En attente de paiement',
        1 => 'Payée',
        2 => 'En préparation',
        3 => 'Envoyée',
        4 => 'Reçue',
        5 => 'Annulée',
        6 => 'Remboursée',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $number = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $locale_code = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Assert\Length(min: 3)]
    private ?string $notes = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $total = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $itemsTotal = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $state = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\OneToMany(targetEntity=ProductSold::class, mappedBy="orderList", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $productsSold;

    /**
     * @ORM\ManyToOne(targetEntity=Shipment::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Shipment $shipment = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $token = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $paymentIntent = null;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Address $shippingAddress = null;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     */
    private ?Address $billingAddress = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $totalWithShipment = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private ?UserInterface $user = null;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->productsSold = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->number;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getLocaleCode(): ?string
    {
        return $this->locale_code;
    }

    public function setLocaleCode(?string $locale_code): self
    {
        $this->locale_code = $locale_code;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getItemsTotal(): ?int
    {
        return $this->itemsTotal;
    }

    public function setItemsTotal(?int $itemsTotal): self
    {
        $this->itemsTotal = $itemsTotal;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

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
    public function getProductsSold(): Collection
    {
        return $this->productsSold;
    }

    public function addProductsSold(ProductSold $productsSold): self
    {
        if (!$this->productsSold->contains($productsSold)) {
            $this->productsSold[] = $productsSold;
            $productsSold->setOrderList($this);
        }

        return $this;
    }

    public function removeProductsSold(ProductSold $productsSold): self
    {
        if ($this->productsSold->removeElement($productsSold)) {
            // set the owning side to null (unless already changed)
            if ($productsSold->getOrderList() === $this) {
                $productsSold->setOrderList(null);
            }
        }

        return $this;
    }

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(?Shipment $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPaymentIntent(): ?string
    {
        return $this->paymentIntent;
    }

    public function setPaymentIntent(?string $paymentIntent): self
    {
        $this->paymentIntent = $paymentIntent;

        return $this;
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?Address $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?Address $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getTotalWithShipment(): ?float
    {
        return $this->totalWithShipment;
    }

    public function setTotalWithShipment(?float $totalWithShipment): self
    {
        $this->totalWithShipment = $totalWithShipment;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}

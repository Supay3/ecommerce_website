<?php

namespace App\Entity\Shop\Order;

use App\Entity\User;
use App\Repository\Shop\Order\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
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
    private ?string $firstname = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank,
        Assert\Length(min: 3, max: 255)
    ]
    private ?string $lastname = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank,
        Assert\Length(min: 3, max: 255)
    ]
    private ?string $street = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Assert\Length(min: 3, max: 255)]
    private ?string $company = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Assert\Length(max: 15)]
    private ?string $phoneNumber = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank,
        Assert\Length(max: 255)
    ]
    private ?string $city = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank,
        Assert\Length(max: 10)
    ]
    private ?string $postcode = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    private ?string $countryCode = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="addresses")
     */
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

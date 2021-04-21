<?php

namespace App\Entity\Shop\Payement;

use App\Repository\Shop\Payement\PayementMethodRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=PayementMethodRepository::class)
 */
class PayementMethod
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
    #[Assert\NotBlank]
    private ?string $code = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $enabled = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\OneToMany(targetEntity=PayementMethodTranslation::class, mappedBy="payementMethod", orphanRemoval=true, cascade={"persist", "remove"})
     */
    #[AcmeAssert\UniqueLocale]
    private Collection $payementMethodTranslations;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->payementMethodTranslations = new ArrayCollection();
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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
            $payementMethodTranslation->setPayementMethod($this);
        }

        return $this;
    }

    public function removePayementMethodTranslation(PayementMethodTranslation $payementMethodTranslation): self
    {
        if ($this->payementMethodTranslations->removeElement($payementMethodTranslation)) {
            // set the owning side to null (unless already changed)
            if ($payementMethodTranslation->getPayementMethod() === $this) {
                $payementMethodTranslation->setPayementMethod(null);
            }
        }

        return $this;
    }
}

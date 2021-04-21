<?php

namespace App\Entity\Shop\Payement;

use App\Entity\Shop\Locale;
use App\Repository\Shop\Payement\PayementMethodTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PayementMethodTranslationRepository::class)
 */
class PayementMethodTranslation
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
     * @ORM\ManyToOne(targetEntity=PayementMethod::class, inversedBy="payementMethodTranslations")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private ?PayementMethod $payementMethod = null;

    /**
     * @ORM\ManyToOne(targetEntity=Locale::class, inversedBy="payementMethodTranslations")
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

    public function getPayementMethod(): ?PayementMethod
    {
        return $this->payementMethod;
    }

    public function setPayementMethod(?PayementMethod $payementMethod): self
    {
        $this->payementMethod = $payementMethod;

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

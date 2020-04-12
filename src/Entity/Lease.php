<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeaseRepository")
 */
class Lease
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signatureDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $effectDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $charges;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $paymentTerm;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ownerLeases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="tenantLeases")
     */
    private $tenant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="leases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    public function __construct()
    {
        $this->tenant = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }

        if (!$this->getUpdatedAt()) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSignatureDate(): ?\DateTimeInterface
    {
        return $this->signatureDate;
    }

    public function setSignatureDate(?\DateTimeInterface $signatureDate): self
    {
        $this->signatureDate = $signatureDate;

        return $this;
    }

    public function getEffectDate(): ?\DateTimeInterface
    {
        return $this->effectDate;
    }

    public function setEffectDate(?\DateTimeInterface $effectDate): self
    {
        $this->effectDate = $effectDate;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(?string $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRent(): ?float
    {
        return $this->rent;
    }

    public function setRent(?float $rent): self
    {
        $this->rent = $rent;

        return $this;
    }

    public function getCharges(): ?float
    {
        return $this->charges;
    }

    public function setCharges(?float $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(?float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getPaymentTerm(): ?int
    {
        return $this->paymentTerm;
    }

    public function setPaymentTerm(?int $paymentTerm): self
    {
        $this->paymentTerm = $paymentTerm;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getTenant(): Collection
    {
        return $this->tenant;
    }

    public function addTenant(User $tenant): self
    {
        if (!$this->tenant->contains($tenant)) {
            $this->tenant[] = $tenant;
        }

        return $this;
    }

    public function removeTenant(User $tenant): self
    {
        if ($this->tenant->contains($tenant)) {
            $this->tenant->removeElement($tenant);
        }

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }
}

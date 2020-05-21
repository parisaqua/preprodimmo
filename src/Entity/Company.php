<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @UniqueEntity("name", message="Cette société existe déjà !")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webSite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company")
     */
    private $member;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $creator;

    public function __construct()
    {
        $this->member = new ArrayCollection();
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

   

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(User $member): self
    {
        if (!$this->member->contains($member)) {
            $this->member[] = $member;
            $member->setCompany($this);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->member->contains($member)) {
            $this->member->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getCompany() === $this) {
                $member->setCompany(null);
            }
        }

        return $this;
    }

    public function getCreator(): ?int
    {
        return $this->creator;
    }

    public function setCreator(int $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
}

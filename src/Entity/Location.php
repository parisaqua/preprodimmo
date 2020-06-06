<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * 
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner une adresse.")
     */
    private $firstLine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secondLine;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner un code postal.")
     * @Assert\Regex("/^[0-9]{5}$/", message="Le format doit être un nombre de 5 chiffres")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner une ville.")
     */
    private $city;

    /**
     * @ORM\Column(type="float", scale=6, precision=8, nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", scale=6, precision=9, nullable=true)
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner un nom pour cette adresse.")
     */
    private $name;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profile;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstLine(): ?string
    {
        return $this->firstLine;
    }

    public function setFirstLine(string $firstLine): self
    {
        $this->firstLine = $firstLine;

        return $this;
    }

    public function getSecondLine(): ?string
    {
        return $this->secondLine;
    }

    public function setSecondLine(?string $secondLine): self
    {
        $this->secondLine = $secondLine;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

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

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }



  
}

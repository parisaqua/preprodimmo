<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class PropertySearch {

    /**
     * @Assert\Range(
     *      min = 11,
     *      max = 750
     * )
     * @var int|null
     */
    private $maxSurface;

    /**
     * @Assert\Range(
     *      min = 10,
     *      max = 400
     * )
     * @var int|null
     */
    private $minSurface;

    /**
     *
     * @var ArrayCollection
     */
    private $options;

    /**
     * @var integer|null
     */
    private $distance;

    /**
     * @var float|null
     */
    private $lat;

    public function __construct() {
        $this->options = new ArrayCollection;
    }

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var float|null
     */
    private $lng;

    /**
     * @return integer|null
     */
    public function getMaxSurface(): ?int {
        return $this->maxSurface;
    }
    
    /**
     * @param integer|null $minSurface
     * @return PropertySearch
     */
    public function setMaxSurface(int $maxSurface): PropertySearch {
        $this->maxSurface = $maxSurface;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getMinSurface(): ?int {
        return $this->minSurface;
    }
    
    /**
     * @param integer|null $minSurface
     * @return PropertySearch
     */
    public function setMinSurface(int $minSurface): PropertySearch {
        $this->minSurface = $minSurface;
        return $this;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getOptions(): ArrayCollection {
        return $this->options;
    }

    /**
     *
     * @param ArrayCollection $options
     * @return void
     */
    public function setOptions(ArrayCollection $options): void {
        $this->options = $options;
    }

    /**
     * @return int|null
     */
    public function getDistance(): ?int
    {
        return $this->distance;
    }

    /**
     * @param int|null $distance
     * @return PropertySearch
     */
    public function setDistance(?int $distance): PropertySearch
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float|null $lat
     * @return PropertySearch
     */
    public function setLat(?float $lat): PropertySearch
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param float|null $lng
     * @return PropertySearch
     */
    public function setLng(?float $lng): PropertySearch
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     * @return PropertySearch
     */
    public function setAddress(?string $address): PropertySearch
    {
        $this->address = $address;
        return $this;
    }




}
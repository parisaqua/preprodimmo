<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 * @UniqueEntity("title", message="Ce titre existe déjà !")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Property
{
    
    const HEAT = [
        0 => 'Electrique',
        1 => 'Gaz individuel',
        2 => 'Gaz collectif',
        3 => 'Fuel collectif',
        4 => 'Cheminée',
        5 => 'sans'
    ];
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Le titre ne peut contenir moins de {{ limit }} caractères",
     *      maxMessage = "Le titre ne peut excéder {{ limit }} caractères"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     *      min = 10,
     *      max = 1200,
     *      minMessage = "La surface doit avoir un minium de  {{ limit }} m²",
     *      maxMessage = "La surface ne peut exéceder {{ limit }} m²"
     * )
     */
    private $surface;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer")
     */
    private $floor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $heat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[0-9]{5}$/", message="Le format doit être un nombre de 5 chiffres")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $sold = false;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $rented = true;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="properties")
     */
    private $options;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTimeInterface|null
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="property", orphanRemoval=true, cascade={"persist"})
     * 
     */
    private $pictures;

    /**
     * 
     * @Assert\All({
     *  @Assert\Image(
     *     maxSize = "4M",
     *     maxSizeMessage="L'image est trop volumineuse, limite à 4 Mo.",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Seuls les JPEG, GIF ou PNG sont accéptés !",
     *     notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier"
     *  )
     * })
     */
    private $pictureFiles;

    /**
     * @ORM\Column(type="float", scale=6, precision=8)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", scale=6, precision=9)
     */
    private $lng;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="propertiesManaged")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="property", cascade={"persist", "remove"}, orphanRemoval=true) 
     * @Assert\Valid()
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lease", mappedBy="property")
     */
    private $leases;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="propertiesOwned")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $landing;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $access;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->options = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->leases = new ArrayCollection();
        $this->owner = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string {
        return (new Slugify())->slugify($this->title);

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

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(?string $access): self
    {
        $this->access = $access;

        return $this;
    }






    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    
    public function getDetailedProperty() {
        if($this->id < 10) {
            return "B0000"."{$this->id}"."-"."{$this->address}"."-"."{$this->postalCode}"."-"."{$this->city}"; 
        }
        elseif($this->id >=10 and $this->id <100) {
            return "B000"."{$this->id}"."-"."{$this->address}"."-"."{$this->postalCode}"."-"."{$this->city}";
        }
        elseif($this->id >=100 and $this->id <1000) {
            return "B00"."{$this->id}"."-"."{$this->address}"."-"."{$this->postalCode}"."-"."{$this->city}";
        }
        elseif($this->id >=1000 and $this->id <10000) {
            return "B0"."{$this->id}"."-"."{$this->address}"."-"."{$this->postalCode}"."-"."{$this->city}";
        }
        else {
            return "B"."{$this->id}"."-"."{$this->address}"."-"."{$this->postalCode}"."-"."{$this->city}";
        }
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Formater le prix
     *
     * @return string
     */
    public function getFormatedPrice(): string {
        return number_format($this->price, 0, '', ' ');
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    /**
     * Convertit le chauffage en string
     *
     * @return string
     */
    public function getHeatType(): string {
        return self::HEAT[$this->heat];
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getRented(): ?bool
    {
        return $this->rented;
    }

    public function setRented(bool $rented): self
    {
        $this->rented = $rented;

        return $this;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeProperty($this);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getPicture(): ?Picture {
        
        if($this->pictures->isEmpty()) {
            return null;
        } 

        return $this->pictures->first();
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setProperty($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getProperty() === $this) {
                $picture->setProperty(null);
            }
        }

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getPictureFiles() {
        return $this->pictureFiles;
    }

    /**
     *
     * @param mixed $pictureFiles
     * 
     * @return Property
     * 
     */
    public function setPictureFiles($pictureFiles): self {
        
        foreach($pictureFiles as $pictureFile) {
            $picture = new Picture();
            $picture->setImageFile($pictureFile);
            $this->addPicture($picture);
            $this->updated_at = new \DateTime('now'); //test pour la mise à jour
        }
        
        $this->pictureFiles = $pictureFiles;

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

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Is the given User the author of this Post?
     *
     * @return bool
     */
    public function isManager(User $user = null)
    {
        return $user && $user->getId() === $this->getManager()->getId();
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setProperty($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getProperty() === $this) {
                $document->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Lease[]
     */
    public function getLeases(): Collection
    {
        return $this->leases;
    }

    public function addLease(Lease $lease): self
    {
        if (!$this->leases->contains($lease)) {
            $this->leases[] = $lease;
            $lease->setProperty($this);
        }

        return $this;
    }

    public function removeLease(Lease $lease): self
    {
        if ($this->leases->contains($lease)) {
            $this->leases->removeElement($lease);
            // set the owning side to null (unless already changed)
            if ($lease->getProperty() === $this) {
                $lease->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(User $owner): self
    {
        if (!$this->owner->contains($owner)) {
            $this->owner[] = $owner;
        }

        return $this;
    }

    public function removeOwner(User $owner): self
    {
        if ($this->owner->contains($owner)) {
            $this->owner->removeElement($owner);
        }

        return $this;
    }

    public function getLanding(): ?string
    {
        return $this->landing;
    }

    public function setLanding(?string $landing): self
    {
        $this->landing = $landing;

        return $this;
    }

   

    

}

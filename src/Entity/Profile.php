<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 * @Vich\Uploadable
 */
class Profile implements \Serializable
{
    
    const GENDER = [
        0 => 'M.',
        1 => 'Mme'
    ];
    
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\Column(type="integer")
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $creator;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="imageName")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @var string
     * @ORM\Column(name="telephoneM", type="string", length=35, nullable=true)
     * 
     */
    protected $telephoneM;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un nom")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $telephoneH;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $telephoneO;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGenderType(): string {
        return self::GENDER[$this->gender];
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * Set telephoneM
     * @param string $telephoneM
     * @return User
     */
    public function setTelephoneM($telephoneM)
    {
        $this->telephoneM = $telephoneM;
 
        return $this;
    }
 
    /**
     * Get telephoneM
     *
     * @return string
     */
    public function getTelephoneM()
    {
        return $this->telephoneM;
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

    
    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->imageName,
        ));
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->imageName,
            ) = unserialize($serialized, array('allowed_classes' => false));
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName() {
        return "{$this->firstName} {$this->lastName}"; 
    }

    public function getTelephoneH(): ?string
    {
        return $this->telephoneH;
    }

    public function setTelephoneH(?string $telephoneH): self
    {
        $this->telephoneH = $telephoneH;

        return $this;
    }

    public function getTelephoneO(): ?string
    {
        return $this->telephoneO;
    }

    public function setTelephoneO(?string $telephoneO): self
    {
        $this->telephoneO = $telephoneO;

        return $this;
    }
}




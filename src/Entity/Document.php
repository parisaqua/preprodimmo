<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @Vich\Uploadable()
 */
class Document
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
    private $filename;

    /**
     * @var File|null
     * @Assert\File(
     *     maxSize = "5M",
     *     maxSizeMessage="Le document est trop volumineuse, limite à 5 Mo.",
     *     mimeTypes={ "application/pdf"  , "application/msword" , "application/vnd.openxmlformats-officedocument.wordprocessingml.document "},
     *     mimeTypesMessage = "Seuls les docuements PDF, Word ou Excel sont accéptés !",
     *     notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     * 
     * @Vich\UploadableField(mapping="property_document", fileNameProperty="filename")
     */
    private $documentFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

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


    /**
     * @return null|File
     */
    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    /**
     * @param null|File $documentFile
     * @return self
     */
    public function setDocumentFile(?File $documentFile): self
    {
        $this->documentFile = $documentFile;
        return $this;
    }


}

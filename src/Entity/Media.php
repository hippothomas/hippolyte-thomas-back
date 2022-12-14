<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Media
{
    #[Groups("media")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("media")]
    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[Vich\UploadableField(mapping: "assets", fileNameProperty: "fileName")]
    private ?File $file = null;

    #[Groups("media")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[Groups("media_details")]
    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Project $project = null;

    #[Groups("media")]
    #[ORM\Column(type: 'datetime')]
    private DateTime $updated;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function update(): void
    {
        $this->setUpdated(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        if ($file instanceof UploadedFile) {
            $this->setUpdated(new \DateTime());
        }

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}

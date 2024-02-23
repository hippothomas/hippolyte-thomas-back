<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Media
{
    #[Groups('media')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('media')]
    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[Vich\UploadableField(mapping: 'assets', fileNameProperty: 'fileName')]
    private ?File $file = null;

    #[Groups('media')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[Groups('media_details')]
    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Project $project = null;

    #[Groups('media')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[Groups('media')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated = null;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $dateTimeNow = new \DateTime('now');

        $this->setUpdated($dateTimeNow);

        if (null === $this->getCreated()) {
            $this->setCreated($dateTimeNow);
        }
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        // Check if the file already exist or has been uploaded
        if (null === $this->getFilename() && null === $this->getFile()) {
            $context->buildViolation('Le fichier ne peut pas être vide !')
                ->atPath('file')
                ->addViolation();
        }
    }
}

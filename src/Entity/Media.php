<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediaRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[Groups("media")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("media")]
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[Groups("media")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[Groups("media_details")]
    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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
}

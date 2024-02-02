<?php

namespace App\Entity;

use App\Repository\SocialRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SocialRepository::class)]
class Social
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Nom ne peut pas être vide !')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Lien ne peut pas être vide !')]
    private ?string $link = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Media $picture = null;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    public function setPicture(Media $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}

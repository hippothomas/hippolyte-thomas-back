<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[Groups("project")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups("project")]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups("project")]
    #[ORM\Column(length: 255)]
    private ?string $introduction = null;

    #[Groups("project")]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups("project")]
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Media::class)]
    private Collection $pictures;

    #[Groups("project")]
    #[ORM\ManyToMany(targetEntity: Technology::class, inversedBy: 'projects')]
    private Collection $technologies;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->technologies = new ArrayCollection();
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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Media $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setProject($this);
        }

        return $this;
    }

    public function removePicture(Media $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProject() === $this) {
                $picture->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Technology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies->add($technology);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        $this->technologies->removeElement($technology);

        return $this;
    }
}

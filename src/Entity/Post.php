<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Loggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\Loggable]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post implements Loggable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['post', 'post_summary'])]
    #[ORM\Column(unique: true)]
    #[Assert\Uuid(message: "Cet UUID n'est pas valide !")]
    private ?string $uuid = null;

    #[Groups(['post', 'post_summary'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Le titre ne peut pas être vide !')]
    private ?string $title = null;

    #[Groups(['post', 'post_summary'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Groups(['post_details'])]
    #[Gedmo\Versioned]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotNull(message: 'Le contenu ne peut pas être vide !')]
    private ?string $content = null;

    #[Groups(['post', 'post_summary'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $featureImage = null;

    #[Groups(['post', 'post_summary'])]
    #[ORM\Column]
    private bool $featured = false;

    /**
     * @var Collection<int, Tag> $tags
     */
    #[Groups(['post_details'])]
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tags;

    #[Groups('post')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le Tag principal doit être défini !')]
    private ?Tag $primaryTag = null;

    #[Groups(['post', 'post_summary'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $published = null;

    #[Groups(['post_details'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[Groups(['post_details'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify((string) $this->title);
        }
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getFeatureImage(): ?Media
    {
        return $this->featureImage;
    }

    public function setFeatureImage(?Media $featureImage): static
    {
        $this->featureImage = $featureImage;

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): static
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getPrimaryTag(): ?Tag
    {
        return $this->primaryTag;
    }

    public function setPrimaryTag(?Tag $primaryTag): static
    {
        $this->primaryTag = $primaryTag;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(?\DateTimeInterface $published): static
    {
        $this->published = $published;

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
}

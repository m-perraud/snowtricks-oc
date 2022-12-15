<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigureRepository::class)]
class Figure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'groupName')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FigGroup $figGroup = null;

    #[ORM\OneToMany(mappedBy: 'linkedFigure', targetEntity: Comment::class)]
    private Collection $linkedFigure;

    #[ORM\OneToMany(mappedBy: 'linkedFigure', targetEntity: Images::class)]
    private Collection $LinkedFigureImages;

    #[ORM\OneToMany(mappedBy: 'linkedFigure', targetEntity: Videos::class)]
    private Collection $linkedFigureVideos;

    public function __construct()
    {
        $this->linkedFigure = new ArrayCollection();
        $this->LinkedFigureImages = new ArrayCollection();
        $this->linkedFigureVideos = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFigGroup(): ?FigGroup
    {
        return $this->figGroup;
    }

    public function setFigGroup(?FigGroup $figGroup): self
    {
        $this->figGroup = $figGroup;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getLinkedFigure(): Collection
    {
        return $this->linkedFigure;
    }

    public function addLinkedFigure(Comment $linkedFigure): self
    {
        if (!$this->linkedFigure->contains($linkedFigure)) {
            $this->linkedFigure->add($linkedFigure);
            $linkedFigure->setLinkedFigure($this);
        }

        return $this;
    }

    public function removeLinkedFigure(Comment $linkedFigure): self
    {
        if ($this->linkedFigure->removeElement($linkedFigure)) {
            // set the owning side to null (unless already changed)
            if ($linkedFigure->getLinkedFigure() === $this) {
                $linkedFigure->setLinkedFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getLinkedFigureImages(): Collection
    {
        return $this->LinkedFigureImages;
    }

    public function addLinkedFigureImage(Images $linkedFigureImage): self
    {
        if (!$this->LinkedFigureImages->contains($linkedFigureImage)) {
            $this->LinkedFigureImages->add($linkedFigureImage);
            $linkedFigureImage->setLinkedFigure($this);
        }

        return $this;
    }

    public function removeLinkedFigureImage(Images $linkedFigureImage): self
    {
        if ($this->LinkedFigureImages->removeElement($linkedFigureImage)) {
            // set the owning side to null (unless already changed)
            if ($linkedFigureImage->getLinkedFigure() === $this) {
                $linkedFigureImage->setLinkedFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getLinkedFigureVideos(): Collection
    {
        return $this->linkedFigureVideos;
    }

    public function addLinkedFigureVideo(Videos $linkedFigureVideo): self
    {
        if (!$this->linkedFigureVideos->contains($linkedFigureVideo)) {
            $this->linkedFigureVideos->add($linkedFigureVideo);
            $linkedFigureVideo->setLinkedFigure($this);
        }

        return $this;
    }

    public function removeLinkedFigureVideo(Videos $linkedFigureVideo): self
    {
        if ($this->linkedFigureVideos->removeElement($linkedFigureVideo)) {
            // set the owning side to null (unless already changed)
            if ($linkedFigureVideo->getLinkedFigure() === $this) {
                $linkedFigureVideo->setLinkedFigure(null);
            }
        }

        return $this;
    }
}

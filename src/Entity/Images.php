<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imageURL = null;

    #[ORM\Column]
    private ?bool $mainImage = null;

    #[ORM\ManyToOne(inversedBy: 'LinkedFigureImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Figure $linkedFigure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(string $imageURL): self
    {
        $this->imageURL = $imageURL;

        return $this;
    }

    public function isMainImage(): ?bool
    {
        return $this->mainImage;
    }

    public function setMainImage(bool $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function getLinkedFigure(): ?Figure
    {
        return $this->linkedFigure;
    }

    public function setLinkedFigure(?Figure $linkedFigure): self
    {
        $this->linkedFigure = $linkedFigure;

        return $this;
    }
}

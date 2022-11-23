<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideosRepository::class)]
class Videos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $videoURL = null;

    #[ORM\ManyToOne(inversedBy: 'linkedFigureVideos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Figure $linkedFigure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoURL(): ?string
    {
        return $this->videoURL;
    }

    public function setVideoURL(string $videoURL): self
    {
        $this->videoURL = $videoURL;

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

<?php

namespace App\Entity;

use App\Repository\FigGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigGroupRepository::class)]
class FigGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'figGroup', targetEntity: Figure::class)]
    private Collection $groupName;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->groupName = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Figure>
     */
    public function getGroupName(): Collection
    {
        return $this->groupName;
    }

    public function addGroupName(Figure $groupName): self
    {
        if (!$this->groupName->contains($groupName)) {
            $this->groupName->add($groupName);
            $groupName->setFigGroup($this);
        }

        return $this;
    }

    public function removeGroupName(Figure $groupName): self
    {
        if ($this->groupName->removeElement($groupName)) {
            // set the owning side to null (unless already changed)
            if ($groupName->getFigGroup() === $this) {
                $groupName->setFigGroup(null);
            }
        }

        return $this;
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
}

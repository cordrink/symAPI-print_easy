<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubDistrictRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubDistrictRepository::class)]
#[ApiResource]
class SubDistrict
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subDistricts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Neighborhood $neighborhood = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNeighborhood(): ?Neighborhood
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?Neighborhood $neighborhood): static
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }
}

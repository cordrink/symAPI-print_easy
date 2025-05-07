<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NeighborhoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeighborhoodRepository::class)]
#[ApiResource]
class Neighborhood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, SubDistrict>
     */
    #[ORM\OneToMany(targetEntity: SubDistrict::class, mappedBy: 'neighborhood')]
    private Collection $subDistricts;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'neighborhood')]
    private Collection $addresses;

    public function __construct()
    {
        $this->subDistricts = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, SubDistrict>
     */
    public function getSubDistricts(): Collection
    {
        return $this->subDistricts;
    }

    public function addSubDistrict(SubDistrict $subDistrict): static
    {
        if (!$this->subDistricts->contains($subDistrict)) {
            $this->subDistricts->add($subDistrict);
            $subDistrict->setNeighborhood($this);
        }

        return $this;
    }

    public function removeSubDistrict(SubDistrict $subDistrict): static
    {
        if ($this->subDistricts->removeElement($subDistrict)) {
            // set the owning side to null (unless already changed)
            if ($subDistrict->getNeighborhood() === $this) {
                $subDistrict->setNeighborhood(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setNeighborhood($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getNeighborhood() === $this) {
                $address->setNeighborhood(null);
            }
        }

        return $this;
    }
}

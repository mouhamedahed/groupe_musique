<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['salle:read', 'salle:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['salle:read', 'salle:write', 'concert:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['salle:read', 'salle:write'])]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Groups(['salle:read', 'salle:write'])]
    private ?int $capacite = null;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }
}

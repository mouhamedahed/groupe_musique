<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BandRepository::class)]
class Band
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['band:read', 'band:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['band:read', 'band:write', 'concert:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?string $origin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?int $year_start = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?int $year_end = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?string $founders = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?int $membres_count = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?string $genre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['band:read', 'band:write'])]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getYearStart(): ?int
    {
        return $this->year_start;
    }

    public function setYearStart(?int $year_start): self
    {
        $this->year_start = $year_start;

        return $this;
    }

    public function getYearEnd(): ?int
    {
        return $this->year_end;
    }

    public function setYearEnd(?int $year_end): self
    {
        $this->year_end = $year_end;

        return $this;
    }

    public function getFounders(): ?string
    {
        return $this->founders;
    }

    public function setFounders(?string $founders): self
    {
        $this->founders = $founders;

        return $this;
    }

    public function getMembresCount(): ?int
    {
        return $this->membres_count;
    }

    public function setMembresCount(?int $membres_count): self
    {
        $this->membres_count = $membres_count;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}

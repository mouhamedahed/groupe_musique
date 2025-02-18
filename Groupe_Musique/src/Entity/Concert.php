<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConcertRepository::class)]
class Concert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['concert:read', 'concert:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['concert:read', 'concert:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['concert:read', 'concert:write'])]
    private ?string $venue = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['concert:read', 'concert:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Salle::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['concert:read', 'concert:write'])]
    private ?Salle $salle = null;

    #[ORM\ManyToMany(targetEntity: Band::class)]
    #[ORM\JoinTable(name: "concert_band")]
    #[Groups(['concert:read', 'concert:write'])]
    private Collection $bands;

    public function __construct()
    {
        $this->bands = new ArrayCollection();
    }

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

    public function getVenue(): ?string
    {
        return $this->venue;
    }

    public function setVenue(?string $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;
        return $this;
    }

    /**
     * @return Collection<int, Band>
     */
    public function getBands(): Collection
    {
        return $this->bands;
    }

    public function addBand(Band $band): self
    {
        if (!$this->bands->contains($band)) {
            $this->bands->add($band);
        }

        return $this;
    }

    public function removeBand(Band $band): self
    {
        $this->bands->removeElement($band);

        return $this;
    }


}

<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ActiviteRepository;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
#[ORM\Table(name: 'activite')]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idActivite = null;

    public function getIdActivite(): ?int
    {
        return $this->idActivite;
    }

    public function setIdActivite(int $idActivite): self
    {
        $this->idActivite = $idActivite;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nomActivite = null;

    public function getNomActivite(): ?string
    {
        return $this->nomActivite;
    }

    public function setNomActivite(string $nomActivite): self
    {
        $this->nomActivite = $nomActivite;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateDebutA = null;

    public function getDateDebutA(): ?\DateTimeInterface
    {
        return $this->dateDebutA;
    }

    public function setDateDebutA(\DateTimeInterface $dateDebutA): self
    {
        $this->dateDebutA = $dateDebutA;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateFinA = null;

    public function getDateFinA(): ?\DateTimeInterface
    {
        return $this->dateFinA;
    }

    public function setDateFinA(\DateTimeInterface $dateFinA): self
    {
        $this->dateFinA = $dateFinA;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'activites')]
    #[ORM\JoinColumn(name: 'idEvent', referencedColumnName: 'idEvent')]
    private ?Evenement $evenement = null;

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;
        return $this;
    }

}

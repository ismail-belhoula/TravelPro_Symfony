<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\BilletavionRepository;

#[ORM\Entity(repositoryClass: BilletavionRepository::class)]
#[ORM\Table(name: 'billetavion')]
class Billetavion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_billetavion = null;

    public function getId_billetavion(): ?int
    {
        return $this->id_billetavion;
    }

    public function setId_billetavion(int $id_billetavion): self
    {
        $this->id_billetavion = $id_billetavion;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $compagnie = null;

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(string $compagnie): self
    {
        $this->compagnie = $compagnie;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $class_Billet = null;

    public function getClass_Billet(): ?string
    {
        return $this->class_Billet;
    }

    public function setClass_Billet(string $class_Billet): self
    {
        $this->class_Billet = $class_Billet;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $villeDepart = null;

    public function getVilleDepart(): ?string
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(string $villeDepart): self
    {
        $this->villeDepart = $villeDepart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $villeArrivee = null;

    public function getVilleArrivee(): ?string
    {
        return $this->villeArrivee;
    }

    public function setVilleArrivee(string $villeArrivee): self
    {
        $this->villeArrivee = $villeArrivee;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateDepart = null;

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateArrivee = null;

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $prix = null;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Voiture::class, inversedBy: 'billetavions')]
    #[ORM\JoinTable(
        name: 'reservation',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_billetAvion', referencedColumnName: 'id_billetavion')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture')
        ]
    )]
    private Collection $voitures;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        if (!$this->voitures instanceof Collection) {
            $this->voitures = new ArrayCollection();
        }
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->getVoitures()->contains($voiture)) {
            $this->getVoitures()->add($voiture);
        }
        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        $this->getVoitures()->removeElement($voiture);
        return $this;
    }

    public function getIdBilletavion(): ?int
    {
        return $this->id_billetavion;
    }

    public function getClassBillet(): ?string
    {
        return $this->class_Billet;
    }

    public function setClassBillet(string $class_Billet): static
    {
        $this->class_Billet = $class_Billet;

        return $this;
    }

}

<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\HotelRepository;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ORM\Table(name: 'hotel')]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_hotel = null;

    public function getId_hotel(): ?int
    {
        return $this->id_hotel;
    }

    public function setId_hotel(int $id_hotel): self
    {
        $this->id_hotel = $id_hotel;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $ville = null;

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $prixParNuit = null;

    public function getPrixParNuit(): ?float
    {
        return $this->prixParNuit;
    }

    public function setPrixParNuit(float $prixParNuit): self
    {
        $this->prixParNuit = $prixParNuit;
        return $this;
    }

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $disponible = null;

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nombreEtoile = null;

    public function getNombreEtoile(): ?int
    {
        return $this->nombreEtoile;
    }

    public function setNombreEtoile(int $nombreEtoile): self
    {
        $this->nombreEtoile = $nombreEtoile;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $typeDeChambre = null;

    public function getTypeDeChambre(): ?string
    {
        return $this->typeDeChambre;
    }

    public function setTypeDeChambre(string $typeDeChambre): self
    {
        $this->typeDeChambre = $typeDeChambre;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateCheckIn = null;

    public function getDateCheckIn(): ?\DateTimeInterface
    {
        return $this->dateCheckIn;
    }

    public function setDateCheckIn(?\DateTimeInterface $dateCheckIn): self
    {
        $this->dateCheckIn = $dateCheckIn;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateCheckOut = null;

    public function getDateCheckOut(): ?\DateTimeInterface
    {
        return $this->dateCheckOut;
    }

    public function setDateCheckOut(?\DateTimeInterface $dateCheckOut): self
    {
        $this->dateCheckOut = $dateCheckOut;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Voiture::class, inversedBy: 'hotels')]
    #[ORM\JoinTable(
        name: 'reservation',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_hotel', referencedColumnName: 'id_hotel')
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

    public function getIdHotel(): ?int
    {
        return $this->id_hotel;
    }

}

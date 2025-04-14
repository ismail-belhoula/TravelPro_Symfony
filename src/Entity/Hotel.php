<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\HotelRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ORM\Table(name: 'hotel')]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_hotel", type: 'integer')]
    private ?int $id_hotel = null;

    public function getIdHotel(): ?int
    {
        return $this->id_hotel;
    }

    public function setIdHotel(int $id_hotel): self
    {
        $this->id_hotel = $id_hotel;
        return $this;
    }

    #[ORM\Column(name: "nom", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom de l'hôtel est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "Le nom ne doit contenir que des lettres et espaces"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
    )]
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

    #[ORM\Column(name: "ville", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La ville est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "La ville ne doit contenir que des lettres et espaces"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "La ville ne peut pas dépasser {{ limit }} caractères"
    )]
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

    #[ORM\Column(name: "PrixParNuit", type: 'decimal', precision: 10, scale: 2, nullable: false)]
    #[Assert\NotBlank(message: "Le prix par nuit est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    #[Assert\Type(
        type: "numeric",
        message: "Le prix doit être un nombre"
    )]
    private ?string $prixParNuit = null;

    public function getPrixParNuit(): ?string
    {
        return $this->prixParNuit;
    }

    public function setPrixParNuit(string $prixParNuit): self
    {
        $this->prixParNuit = $prixParNuit;
        return $this;
    }

    #[ORM\Column(name: "disponible", type: 'boolean', nullable: false)]
    private ?bool $disponible = true;

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;
        return $this;
    }

    #[ORM\Column(name: "NombreEtoile", type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Le nombre d'étoiles est obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 7,
        notInRangeMessage: "Le nombre d'étoiles doit être entre {{ min }} et {{ max }}"
    )]
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

    #[ORM\Column(name: "TypeDeChambre", type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Le type de chambre est obligatoire")]
    #[Assert\Choice(
        choices: ["single", "double", "triple"],
        message: "Le type de chambre doit être single, double ou triple"
    )]
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

    #[ORM\Column(name: "DateCheckIn", type: 'date', nullable: true)]
    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: "La date de check-in doit être aujourd'hui ou ultérieure"
    )]
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

    #[ORM\Column(name: "DateCheckOut", type: 'date', nullable: true)]
    #[Assert\Expression(
        "this.getDateCheckIn() === null or this.getDateCheckOut() === null or this.getDateCheckOut() > this.getDateCheckIn()",
        message: "La date de check-out doit être après la date de check-in"
    )]
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

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'hotel', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $reservations;
    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setHotel($this);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getHotel() === $this) {
                $reservation->setHotel(null);
            }
        }
        return $this;
    }
}
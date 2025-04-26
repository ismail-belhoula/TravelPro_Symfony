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

    #[ORM\Column(name: "nom", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom de l'hôtel est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "Le nom ne doit contenir que des lettres et espaces"
    )]
    private ?string $nom = null;

    #[ORM\Column(name: "ville", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La ville est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "La ville ne doit contenir que des lettres et espaces"
    )]
    private ?string $ville = null;

    #[ORM\Column(name: "PrixParNuit", type: 'decimal', precision: 10, scale: 2, nullable: false)]
    #[Assert\NotBlank(message: "Le prix par nuit est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    private ?float $prixParNuit = null;

    #[ORM\Column(name: "disponible", type: 'boolean', nullable: false)]
    private ?bool $disponible = true;

    #[ORM\Column(name: "NombreEtoile", type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Le nombre d'étoiles est obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 7,
        notInRangeMessage: "Le nombre d'étoiles doit être entre {{ min }} et {{ max }}"
    )]
    private ?int $nombreEtoile = null;

    #[ORM\Column(name: "TypeDeChambre", type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Le type de chambre est obligatoire")]
    #[Assert\Choice(
        choices: ["single", "double", "triple", "suite"],
        message: "Le type de chambre doit être single, double, triple ou suite"
    )]
    private ?string $typeDeChambre = null;

    #[ORM\Column(name: "DateCheckIn", type: 'date', nullable: true)]
    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: "La date de check-in doit être aujourd'hui ou ultérieure"
    )]
    private ?\DateTimeInterface $dateCheckIn = null;

    #[ORM\Column(name: "DateCheckOut", type: 'date', nullable: true)]
    #[Assert\Expression(
        "this.getDateCheckIn() === null or this.getDateCheckOut() === null or this.getDateCheckOut() > this.getDateCheckIn()",
        message: "La date de check-out doit être après la date de check-in"
    )]
    private ?\DateTimeInterface $dateCheckOut = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'hotel', cascade: ['persist', 'remove'])]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->disponible = true;
    }

    // Getters and Setters with both naming conventions
    public function getId(): ?int
    {
        return $this->id_hotel;
    }

    public function getId_hotel(): ?int
    {
        return $this->id_hotel;
    }

    // ... rest of getters and setters ...

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
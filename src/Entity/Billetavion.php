<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\BilletavionRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BilletavionRepository::class)]
#[ORM\Table(name: 'billetavion')]
class Billetavion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_billetavion", type: 'integer')]
    private ?int $id_billetavion = null;

    #[ORM\Column(name: "compagnie", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La compagnie est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "La compagnie ne doit contenir que des lettres"
    )]
    private ?string $compagnie = null;

    #[ORM\Column(name: "class_Billet", type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank(message: "La classe est obligatoire")]
    #[Assert\Choice(
        choices: ["economique", "affaire", "premiere"],
        message: "La classe doit être économique, affaire ou première"
    )]
    private ?string $class_Billet = null;

    #[ORM\Column(name: "villeDepart", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La ville de départ est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "La ville de départ ne doit contenir que des lettres"
    )]
    private ?string $villeDepart = null;

    #[ORM\Column(name: "villeArrivee", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La ville d'arrivée est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "La ville d'arrivée ne doit contenir que des lettres"
    )]
    private ?string $villeArrivee = null;

    #[ORM\Column(name: "dateDepart", type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de départ est obligatoire")]
    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: "La date de départ doit être aujourd'hui ou ultérieure"
    )]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(name: "dateArrivee", type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date d'arrivée est obligatoire")]
    #[Assert\Expression(
        "this.getDateDepart() === null or this.getDateArrivee() === null or this.getDateArrivee() > this.getDateDepart()",
        message: "La date d'arrivée doit être après la date de départ"
    )]
    private ?\DateTimeInterface $dateArrivee = null;

    #[ORM\Column(name: "prix", type: 'decimal', precision: 10, scale: 2, nullable: false)]
    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    private ?float $prix = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'billetAvion', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId_billetavion(): ?int
    {
        return $this->id_billetavion;
    }

    public function setId_billetavion(int $id_billetavion): self
    {
        $this->id_billetavion = $id_billetavion;
        return $this;
    }

    // ... other getters and setters remain unchanged ...

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
            $reservation->setBilletAvion($this);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getBilletAvion() === $this) {
                $reservation->setBilletAvion(null);
            }
        }
        return $this;
    }
}
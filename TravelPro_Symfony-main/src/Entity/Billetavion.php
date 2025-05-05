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
    #[Assert\Length(
        max: 255,
        maxMessage: "La compagnie ne peut pas dépasser {{ limit }} caractères"
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
    #[Assert\Length(
        max: 255,
        maxMessage: "La ville de départ ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $villeDepart = null;

    #[ORM\Column(name: "villeArrivee", type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La ville d'arrivée est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "La ville d'arrivée ne doit contenir que des lettres"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "La ville d'arrivée ne peut pas dépasser {{ limit }} caractères"
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
    #[Assert\Type(
        type: "numeric",
        message: "Le prix doit être un nombre"
    )]
    private ?string $prix = null;

    public function getIdBilletavion(): ?int
    {
        return $this->id_billetavion;
    }

    public function setIdBilletavion(int $id): self
    {
        $this->id_billetavion = $id;
        return $this;
    }

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(string $compagnie): self
    {
        $this->compagnie = $compagnie;
        return $this;
    }

    public function getClassBillet(): ?string
    {
        return $this->class_Billet;
    }

    public function setClassBillet(string $class_Billet): self
    {
        $this->class_Billet = $class_Billet;
        return $this;
    }

    public function getVilleDepart(): ?string
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(string $villeDepart): self
    {
        $this->villeDepart = $villeDepart;
        return $this;
    }

    public function getVilleArrivee(): ?string
    {
        return $this->villeArrivee;
    }

    public function setVilleArrivee(string $villeArrivee): self
    {
        $this->villeArrivee = $villeArrivee;
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'billetAvion', cascade: ['persist', 'remove'], orphanRemoval: true)]
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


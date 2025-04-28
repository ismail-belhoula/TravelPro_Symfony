<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\VoitureRepository;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[ORM\Table(name: 'voiture')]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_voiture", type: 'integer')]
    private ?int $id_voiture = null;

    public function getId_voiture(): ?int
    {
        return $this->id_voiture;
    }

    public function setId_voiture(int $id_voiture): self
    {
        $this->id_voiture = $id_voiture;
        return $this;
    }

    #[ORM\Column(name: "Marque", type: 'string', nullable: false)]
    private ?string $marque = null;

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;
        return $this;
    }

    #[ORM\Column(name: "Modele", type: 'string', nullable: false)]
    private ?string $modele = null;

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;
        return $this;
    }

    #[ORM\Column(name: "Annee", type: 'integer', nullable: false)]
    private ?int $annee = null;

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    #[ORM\Column(name: "PrixParJour", type: 'float', nullable: false)]
    private ?float $prixParJour = null;

    public function getPrixParJour(): ?float
    {
        return $this->prixParJour;
    }

    public function setPrixParJour(float $prixParJour): self
    {
        $this->prixParJour = $prixParJour;
        return $this;
    }

    #[ORM\Column(name: "Disponible", type: 'boolean', nullable: true)]
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

    #[ORM\Column(name: "DateDeLocation", type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateDeLocation = null;

    public function getDateDeLocation(): ?\DateTimeInterface
    {
        return $this->dateDeLocation;
    }

    public function setDateDeLocation(?\DateTimeInterface $dateDeLocation): self
    {
        $this->dateDeLocation = $dateDeLocation;
        return $this;
    }

    #[ORM\Column(name: "DateDeRemise", type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateDeRemise = null;

    public function getDateDeRemise(): ?\DateTimeInterface
    {
        return $this->dateDeRemise;
    }

    public function setDateDeRemise(?\DateTimeInterface $dateDeRemise): self
    {
        $this->dateDeRemise = $dateDeRemise;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'voiture', cascade: ['persist', 'remove'], orphanRemoval: true)]
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
            $reservation->setVoiture($this);
        }
        return $this;
    }
    
    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getVoiture() === $this) {
                $reservation->setVoiture(null);
            }
        }
        return $this;
    }
}
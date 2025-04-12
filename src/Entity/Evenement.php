<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\EvenementRepository;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ORM\Table(name: 'evenement')]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer',name:'idEvent')]
    private ?int $idEvent = null;

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function setIdEvent(int $idEvent): self
    {
        $this->idEvent = $idEvent;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false,name:'nomEvent')]
    private ?string $nomEvent = null;

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }

    public function setNomEvent(string $nomEvent): self
    {
        $this->nomEvent = $nomEvent;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $lieu = null;

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false,name:'dateDebutE')]
    private ?\DateTimeInterface $dateDebutE = null;

    public function getDateDebutE(): ?\DateTimeInterface
    {
        return $this->dateDebutE;
    }

    public function setDateDebutE(\DateTimeInterface $dateDebutE): self
    {
        $this->dateDebutE = $dateDebutE;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false,name:'dateFinE')]
    private ?\DateTimeInterface $dateFinE = null;

    public function getDateFinE(): ?\DateTimeInterface
    {
        return $this->dateFinE;
    }

    public function setDateFinE(\DateTimeInterface $dateFinE): self
    {
        $this->dateFinE = $dateFinE;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $image = null;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false,name:'idReservation')]
    private ?int $idReservation = null;

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function setIdReservation(int $idReservation): self
    {
        $this->idReservation = $idReservation;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $latitude = null;

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $longitude = null;

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'evenement')]
    private Collection $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        if (!$this->activites instanceof Collection) {
            $this->activites = new ArrayCollection();
        }
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->getActivites()->contains($activite)) {
            $this->getActivites()->add($activite);
        }
        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        $this->getActivites()->removeElement($activite);
        return $this;
    }

}
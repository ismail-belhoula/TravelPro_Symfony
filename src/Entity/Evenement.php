<?php

namespace App\Entity;

<<<<<<< HEAD
use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ORM\Table(name: 'evenement')]
#[Vich\Uploadable]

=======
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\EvenementRepository;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ORM\Table(name: 'evenement')]
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer',name:'idEvent')]
<<<<<<< HEAD
    private ?int $id_event = null;

    #[ORM\Column(type: 'string', nullable: false ,name:'nomEvent')]
    #[Assert\NotBlank(message: "Le nom de l'événement est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le nom doit faire au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom_event = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le lieu est obligatoire.")]
    private ?string $lieu = null;

    #[ORM\Column(type: 'date', nullable: false,name:'dateDebutE')]
    #[Assert\NotNull(message: "La date de début est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date de début doit être aujourd'hui ou dans le futur.")]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: 'date', nullable: false,name:'dateFinE')]
    #[Assert\NotNull(message: "La date de fin est obligatoire.")]
    #[Assert\Expression(
        "this.getDateFin() >= this.getDateDebut()",
        message: "La date de fin doit être après la date de début."
    )]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le type d'événement est obligatoire.")]
    private ?string $type = null;

    #[ORM\Column(type: 'string', nullable: false ,name:'image')]
    
    private ?string $image = null;
    #[Vich\UploadableField(mapping: "event_images", fileNameProperty: "image")]
    #[Assert\Image(maxSize: "50M")]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'float')]
private ?float $latitude = null;

#[ORM\Column(type: 'float')]
private ?float $longitude = null;

    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'evenement')]
    private Collection $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }

    public function getId_event(): ?int
    {
        return $this->id_event;
    }

    public function setId_event(int $id_event): self
    {
        $this->id_event = $id_event;
        return $this;
    }

    public function getNom_event(): ?string
    {
        return $this->nom_event;
    }

    public function setNom_event(string $nom_event): self
    {
        $this->nom_event = $nom_event;
        return $this;
    }

=======
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

>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

<<<<<<< HEAD
    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDate_debut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDate_fin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        return $this;
    }

=======
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

>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

<<<<<<< HEAD
=======
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $image = null;

>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    public function getImage(): ?string
    {
        return $this->image;
    }

<<<<<<< HEAD
    public function setImage(?string $image): static
=======
    public function setImage(string $image): self
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    {
        $this->image = $image;
        return $this;
    }
<<<<<<< HEAD
    

    public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;

   
}
    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getLatitude(): ?float
{
    return $this->latitude;
}

public function setLatitude(float $latitude): static
{
    $this->latitude = $latitude;
    return $this;
}

public function getLongitude(): ?float
{
    return $this->longitude;
}

public function setLongitude(float $longitude): static
{
    $this->longitude = $longitude;
    return $this;
}
=======

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

>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
<<<<<<< HEAD
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->setEvenement($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            if ($activite->getEvenement() === $this) {
                $activite->setEvenement(null);
            }
        }

        return $this;
    }

    // Pour la compatibilité avec des noms de méthode différents
    public function getIdEvent(): ?int
    {
        return $this->id_event;
    }

    public function getNomEvent(): ?string
    {
        return $this->nom_event;
    }

    public function setNomEvent(string $nom_event): static
    {
        return $this->setNom_event($nom_event);
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        return $this->setDate_debut($date_debut);
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        return $this->setDate_fin($date_fin);
    }
}
=======
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
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610

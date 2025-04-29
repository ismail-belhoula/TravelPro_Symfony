<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
#[ORM\Table(name: 'activite')]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer',name:'idActivite')]
    private ?int $id_activite = null;

    #[ORM\Column(type: 'string', nullable: false,name:'nomActivite')]
    #[Assert\NotBlank(message: "Le nom de l'activité est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom_activite = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'date', nullable: false,name:'dateDebutA')]
    #[Assert\NotNull(message: "La date de début est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date de début doit être aujourd'hui ou dans le futur.")]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: 'date', nullable: false,name:'dateFinA')]
    #[Assert\NotNull(message: "La date de fin est obligatoire.")]
    #[Assert\Expression(
        "this.getDateFin() >= this.getDateDebut()",
        message: "La date de fin doit être après la date de début."
    )]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'activites')]
    #[ORM\JoinColumn(name: 'idEvent', referencedColumnName: 'idEvent', nullable: false)]
    #[Assert\NotNull(message: "L'événement associé est obligatoire.")]
    private ?Evenement $evenement = null;
    

    // GETTERS / SETTERS identiques à ce que tu avais déjà :
    // ...

    public function getId(): ?int
    {
        return $this->id_activite;
    }

    public function setId(int $id_activite): self
    {
        $this->id_activite = $id_activite;
        return $this;
    }

    public function getNom_activite(): ?string
    {
        return $this->nom_activite;
    }

    public function setNom_activite(string $nom_activite): self
    {
        $this->nom_activite = $nom_activite;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;
        return $this;
    }

    public function getNomActivite(): ?string
    {
        return $this->nom_activite;
    }

    public function setNomActivite(string $nom_activite): static
    {
        $this->nom_activite = $nom_activite;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;
        return $this;
    }
}

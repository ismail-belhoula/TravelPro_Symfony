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
    #[ORM\Column(type: 'integer')]
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

    #[ORM\Column(type: 'string', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
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

    #[ORM\Column(type: 'integer', nullable: false)]
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

    #[ORM\Column(type: 'decimal', nullable: false)]
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

    #[ORM\Column(type: 'date', nullable: true)]
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

    #[ORM\Column(type: 'date', nullable: true)]
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

    #[ORM\ManyToMany(targetEntity: Billetavion::class, inversedBy: 'voitures')]
    #[ORM\JoinTable(
        name: 'reservation',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_billetAvion', referencedColumnName: 'id_billetavion')
        ]
    )]
    private Collection $billetavions;

    public function __construct()
    {
        $this->billetavions = new ArrayCollection();
    }

    /**
     * @return Collection<int, Billetavion>
     */
    public function getBilletavions(): Collection
    {
        if (!$this->billetavions instanceof Collection) {
            $this->billetavions = new ArrayCollection();
        }
        return $this->billetavions;
    }

    public function addBilletavion(Billetavion $billetavion): self
    {
        if (!$this->getBilletavions()->contains($billetavion)) {
            $this->getBilletavions()->add($billetavion);
        }
        return $this;
    }

    public function removeBilletavion(Billetavion $billetavion): self
    {
        $this->getBilletavions()->removeElement($billetavion);
        return $this;
    }

    public function getIdVoiture(): ?int
    {
        return $this->id_voiture;
    }

}

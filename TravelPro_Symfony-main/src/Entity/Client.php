<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ClientRepository;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'client')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_client = null;

    public function getId_client(): ?int
    {
        return $this->id_client;
    }

    public function setId_client(int $id_client): self
    {
        $this->id_client = $id_client;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide.")]
    #[Assert\Length(min: 10, max: 255, minMessage: "L'adresse doit contenir au moins {{ limit }} caractères.", maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $addresse_client = null;

    public function getAddresse_client(): ?string
    {
        return $this->addresse_client;
    }

    public function setAddresse_client(string $addresse_client): self
    {
        $this->addresse_client = $addresse_client;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide.")]
    #[Assert\Length(min: 8, max: 8, minMessage: "Le numéro de téléphone doit contenir au moins {{ limit }} caractères.", maxMessage: "Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: "/^\+?[0-9]{8,8}$/",
        message: "Le numéro de téléphone doit être valide (ex: +1234567890)."
    )]
    private ?string $num_tel_client = null;

    public function getNum_tel_client(): ?string
    {
        return $this->num_tel_client;
    }

    public function setNum_tel_client(string $num_tel_client): self
    {
        $this->num_tel_client = $num_tel_client;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'clients')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur')]
    private ?Utilisateur $utilisateur = null;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Url(message: "L'URL de l'image doit être valide.")]
    private ?string $image_url = null;

    public function getImage_url(): ?string
    {
        return $this->image_url;
    }

    public function setImage_url(?string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'client')]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->demandeValidations = new ArrayCollection();
        $this->voitures = new ArrayCollection();
    }

    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->getCommandes()->contains($commande)) {
            $this->getCommandes()->add($commande);
        }
        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        $this->getCommandes()->removeElement($commande);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: DemandeValidation::class, mappedBy: 'client')]
    private Collection $demandeValidations;

    public function getDemandeValidations(): Collection
    {
        return $this->demandeValidations;
    }

    public function addDemandeValidation(DemandeValidation $demandeValidation): self
    {
        if (!$this->getDemandeValidations()->contains($demandeValidation)) {
            $this->getDemandeValidations()->add($demandeValidation);
        }
        return $this;
    }

    public function removeDemandeValidation(DemandeValidation $demandeValidation): self
    {
        $this->getDemandeValidations()->removeElement($demandeValidation);
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Voiture::class, inversedBy: 'clients')]
    #[ORM\JoinTable(
        name: 'reservation',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id_client')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture')
        ]
    )]
    private Collection $voitures;

    public function getVoitures(): Collection
    {
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
}

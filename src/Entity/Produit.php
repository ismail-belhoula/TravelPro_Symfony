<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
<<<<<<< HEAD
use Symfony\Component\Validator\Constraints as Assert;
=======

>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
use App\Repository\ProduitRepository;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Table(name: 'produit')]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_produit = null;

<<<<<<< HEAD
    public function getId(): ?int
=======
    public function getId_produit(): ?int
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    {
        return $this->id_produit;
    }

    public function setId_produit(int $id_produit): self
    {
        $this->id_produit = $id_produit;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom_produit = null;
<<<<<<< HEAD
    
=======
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610

    public function getNom_produit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNom_produit(string $nom_produit): self
    {
        $this->nom_produit = $nom_produit;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(type: 'decimal', nullable: true)]
    #[Assert\NotBlank]
=======
    #[ORM\Column(type: 'decimal', nullable: false)]
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    private ?float $prix_achat = null;

    public function getPrix_achat(): ?float
    {
        return $this->prix_achat;
    }

    public function setPrix_achat(float $prix_achat): self
    {
        $this->prix_achat = $prix_achat;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
<<<<<<< HEAD
    #[Assert\NotBlank]
    private ?int $quantite_produit = null;    
=======
    private ?int $quantite_produit = null;
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610

    public function getQuantite_produit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantite_produit(int $quantite_produit): self
    {
        $this->quantite_produit = $quantite_produit;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
<<<<<<< HEAD
    private ?float $prix_vente = null;    
=======
    private ?float $prix_vente = null;
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610

    public function getPrix_vente(): ?float
    {
        return $this->prix_vente;
    }

    public function setPrix_vente(?float $prix_vente): self
    {
        $this->prix_vente = $prix_vente;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
<<<<<<< HEAD
    #[Assert\NotBlank( 
        message: 'il faut choisir un photo nom du produit.'
    )]
=======
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    private ?string $image_path = null;

    public function getImage_path(): ?string
    {
        return $this->image_path;
    }

    public function setImage_path(?string $image_path): self
    {
        $this->image_path = $image_path;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'produits')]
    #[ORM\JoinTable(
        name: 'commande_produit',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id_commande')
        ]
    )]
    private Collection $commandes;

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        if (!$this->commandes instanceof Collection) {
            $this->commandes = new ArrayCollection();
        }
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

    #[ORM\ManyToMany(targetEntity: Panier::class, inversedBy: 'produits')]
    #[ORM\JoinTable(
        name: 'panier_produit',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_panier', referencedColumnName: 'id_panier')
        ]
    )]
    private Collection $paniers;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        if (!$this->paniers instanceof Collection) {
            $this->paniers = new ArrayCollection();
        }
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->getPaniers()->contains($panier)) {
            $this->getPaniers()->add($panier);
        }
        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        $this->getPaniers()->removeElement($panier);
        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->id_produit;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getPrixAchat(): ?string
    {
        return $this->prix_achat;
    }

    public function setPrixAchat(string $prix_achat): static
    {
        $this->prix_achat = $prix_achat;

        return $this;
    }

    public function getQuantiteProduit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantiteProduit(int $quantite_produit): static
    {
        $this->quantite_produit = $quantite_produit;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prix_vente;
    }

    public function setPrixVente(?string $prix_vente): static
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(?string $image_path): static
    {
        $this->image_path = $image_path;

        return $this;
    }

<<<<<<< HEAD
}
=======
}
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610

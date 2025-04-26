<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProduitRepository;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Table(name: 'produit')]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_produit = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire')]
    private ?string $nom_produit = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    #[Assert\NotBlank(message: 'Le prix d\'achat est obligatoire')]
    #[Assert\Positive(message: 'Le prix doit être positif')]
    private ?float $prix_achat = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    #[Assert\PositiveOrZero(message: 'La quantité doit être positive ou nulle')]
    private ?int $quantite_produit = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero(message: 'Le prix de vente doit être positif ou nul')]
    private ?float $prix_vente = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'L\'image du produit est obligatoire')]
    private ?string $image_path = null;

    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'produits')]
    #[ORM\JoinTable(name: 'commande_produit')]
    private Collection $commandes;

    #[ORM\ManyToMany(targetEntity: Panier::class, inversedBy: 'produits')]
    #[ORM\JoinTable(name: 'panier_produit')]
    private Collection $paniers;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    // Getters and setters with both naming conventions for compatibility
    public function getId(): ?int
    {
        return $this->id_produit;
    }

    public function getId_produit(): ?int
    {
        return $this->id_produit;
    }

    public function setId_produit(int $id_produit): self
    {
        $this->id_produit = $id_produit;
        return $this;
    }

    // Remaining getters and setters...
    // (Keep both naming conventions for compatibility)

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
        }
        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        $this->commandes->removeElement($commande);
        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
        }
        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        $this->paniers->removeElement($panier);
        return $this;
    }
}
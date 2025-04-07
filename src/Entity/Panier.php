<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PanierRepository;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ORM\Table(name: 'panier')]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_panier = null;

    public function getId_panier(): ?int
    {
        return $this->id_panier;
    }

    public function setId_panier(int $id_panier): self
    {
        $this->id_panier = $id_panier;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $id_client = null;

    public function getId_client(): ?int
    {
        return $this->id_client;
    }

    public function setId_client(?int $id_client): self
    {
        $this->id_client = $id_client;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $montant_total = null;

    public function getMontant_total(): ?float
    {
        return $this->montant_total;
    }

    public function setMontant_total(float $montant_total): self
    {
        $this->montant_total = $montant_total;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'paniers')]
    #[ORM\JoinTable(
        name: 'panier_produit',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_panier', referencedColumnName: 'id_panier')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit')
        ]
    )]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        if (!$this->produits instanceof Collection) {
            $this->produits = new ArrayCollection();
        }
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->getProduits()->contains($produit)) {
            $this->getProduits()->add($produit);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->getProduits()->removeElement($produit);
        return $this;
    }

    public function getIdPanier(): ?int
    {
        return $this->id_panier;
    }

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function setIdClient(?int $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montant_total;
    }

    public function setMontantTotal(string $montant_total): static
    {
        $this->montant_total = $montant_total;

        return $this;
    }

}

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
    #[ORM\Column(name: 'id_panier', type: 'integer')]
    private ?int $id_panier = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $id_client = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private ?float $montant_total = 0.0;

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
        $this->montant_total = 0.0;
    }

    public function getId(): ?int
    {
        return $this->id_panier;
    }

    public function getId_panier(): ?int
    {
        return $this->id_panier;
    }

    public function setId_panier(int $id_panier): self
    {
        $this->id_panier = $id_panier;
        return $this;
    }

    public function getId_client(): ?int
    {
        return $this->id_client;
    }

    public function setId_client(?int $id_client): self
    {
        $this->id_client = $id_client;
        return $this;
    }

    public function getMontant_total(): ?float
    {
        return $this->montant_total;
    }

    public function setMontant_total(float $montant_total): self
    {
        $this->montant_total = $montant_total;
        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produits->removeElement($produit);
        return $this;
    }
}
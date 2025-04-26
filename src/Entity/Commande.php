<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CommandeRepository;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commande')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_commande = null;

    public function getId_commande(): ?int
    {
        return $this->id_commande;
    }

    public function setId_commande(int $id_commande): self
    {
        $this->id_commande = $id_commande;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id_client')]
    private ?Client $client = null;

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
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

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_commande = null;

    public function getDate_commande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDate_commande(?\DateTimeInterface $date_commande): self
    {
        $this->date_commande = $date_commande;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinTable(
        name: 'commande_produit',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id_commande')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit')
        ]
    )]
    private Collection $produits;

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

}

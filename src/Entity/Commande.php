<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commande')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_commande', type: 'integer')]
    private ?int $id_commande = null;

    public function getId(): ?int
    {
        // Cette méthode est utilisée par Doctrine comme identifiant
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

<<<<<<< HEAD
    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank]
=======
    #[ORM\Column(type: 'decimal', nullable: false)]
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    private ?float $montant_total = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(type: 'string', nullable: true)]
<<<<<<< HEAD
    #[Assert\NotBlank( 
        message: 'il faut remplir la status.'
    )]
=======
>>>>>>> b39d0b1912e6a6ee1011519a7b43b8945158b610
    private ?string $status = null;

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

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montant_total;
    }

    public function setMontantTotal(float $montant_total): self
    {
        $this->montant_total = $montant_total;
        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(?\DateTimeInterface $date_commande): self
    {
        $this->date_commande = $date_commande;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
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

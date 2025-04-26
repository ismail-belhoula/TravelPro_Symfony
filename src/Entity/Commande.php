
<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commande')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_commande', type: 'integer')]
    private ?int $id_commande = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id_client')]
    private ?Client $client = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    #[Assert\NotBlank(message: 'Le montant total est obligatoire.')]
    #[Assert\PositiveOrZero(message: 'Le montant total doit être positif ou zéro.')]
    private ?float $montant_total = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Le statut est obligatoire.')]
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
        $this->date_commande = new \DateTime();
    }

    // ID getters and setters for compatibility
    public function getId(): ?int
    {
        return $this->id_commande;
    }

    public function getId_commande(): ?int
    {
        return $this->id_commande;
    }

    public function setId_commande(int $id_commande): self
    {
        $this->id_commande = $id_commande;
        return $this;
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

    public function getMontant_total(): ?float
    {
        return $this->montant_total;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montant_total;
    }

    public function setMontant_total(float $montant_total): self
    {
        $this->montant_total = $montant_total;
        return $this;
    }

    public function setMontantTotal(float $montant_total): self
    {
        return $this->setMontant_total($montant_total);
    }

    public function getDate_commande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDate_commande(?\DateTimeInterface $date_commande): self
    {
        $this->date_commande = $date_commande;
        return $this;
    }

    public function setDateCommande(?\DateTimeInterface $date_commande): self
    {
        return $this->setDate_commande($date_commande);
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
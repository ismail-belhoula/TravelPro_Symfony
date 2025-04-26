<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\DeponseRepository;

#[ORM\Entity(repositoryClass: DeponseRepository::class)]
#[ORM\Table(name: 'deponse')]
class Deponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_deponse = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'deponses')]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id_produit', nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    #[Assert\NotBlank(message: 'Le prix d\'achat est obligatoire')]
    #[Assert\Positive(message: 'Le prix d\'achat doit être un nombre positif')]
    private ?float $prix_achat = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    #[Assert\Positive(message: 'La quantité doit être un nombre positif')]
    private ?int $quantite_produit = null;

    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotNull(message: 'La date d\'achat est obligatoire')]
    private ?\DateTimeInterface $Date_achat = null;

    // Both naming conventions for compatibility
    public function getId(): ?int
    {
        return $this->id_deponse;
    }

    public function getId_deponse(): ?int
    {
        return $this->id_deponse;
    }

    public function getIdDeponse(): ?int
    {
        return $this->id_deponse;
    }

    public function setId_deponse(int $id_deponse): self
    {
        $this->id_deponse = $id_deponse;
        return $this;
    }

    public function getPrix_achat(): ?float
    {
        return $this->prix_achat;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prix_achat;
    }

    public function setPrix_achat(float $prix_achat): self
    {
        $this->prix_achat = $prix_achat;
        return $this;
    }

    public function setPrixAchat(float $prix_achat): self
    {
        return $this->setPrix_achat($prix_achat);
    }

    public function getQuantite_produit(): ?int
    {
        return $this->quantite_produit;
    }

    public function getQuantiteProduit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantite_produit(int $quantite_produit): self
    {
        $this->quantite_produit = $quantite_produit;
        return $this;
    }

    public function setQuantiteProduit(int $quantite_produit): self
    {
        return $this->setQuantite_produit($quantite_produit);
    }

    public function getDate_achat(): ?\DateTimeInterface
    {
        return $this->Date_achat;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->Date_achat;
    }

    public function setDate_achat(\DateTimeInterface $Date_achat): self
    {
        $this->Date_achat = $Date_achat;
        return $this;
    }

    public function setDateAchat(\DateTimeInterface $Date_achat): self
    {
        return $this->setDate_achat($Date_achat);
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }
}
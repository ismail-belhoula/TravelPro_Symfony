<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\DeponseRepository;

#[ORM\Entity(repositoryClass: DeponseRepository::class)]
#[ORM\Table(name: 'deponse')]
class Deponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_deponse = null;

    public function getId_deponse(): ?int
    {
        return $this->id_deponse;
    }

    public function setId_deponse(int $id_deponse): self
    {
        $this->id_deponse = $id_deponse;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_produit = null;

    public function getId_produit(): ?int
    {
        return $this->id_produit;
    }

    public function setId_produit(int $id_produit): self
    {
        $this->id_produit = $id_produit;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
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
    private ?int $quantite_produit = null;

    public function getQuantite_produit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantite_produit(int $quantite_produit): self
    {
        $this->quantite_produit = $quantite_produit;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $Date_achat = null;

    public function getDate_achat(): ?\DateTimeInterface
    {
        return $this->Date_achat;
    }

    public function setDate_achat(\DateTimeInterface $Date_achat): self
    {
        $this->Date_achat = $Date_achat;
        return $this;
    }

    public function getIdDeponse(): ?int
    {
        return $this->id_deponse;
    }

    public function getIdProduit(): ?int
    {
        return $this->id_produit;
    }

    public function setIdProduit(int $id_produit): static
    {
        $this->id_produit = $id_produit;

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

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->Date_achat;
    }

    public function setDateAchat(\DateTimeInterface $Date_achat): static
    {
        $this->Date_achat = $Date_achat;

        return $this;
    }

}

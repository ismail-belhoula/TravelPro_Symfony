<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

use App\Repository\RevenueRepository;

#[ORM\Entity(repositoryClass: RevenueRepository::class)]
#[ORM\Table(name: 'revenue')]
class Revenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_revenue = null;

    public function getId_revenue(): ?int
    {
        return $this->id_revenue;
    }

    public function setId_revenue(int $id_revenue): self
    {
        $this->id_revenue = $id_revenue;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank( 
        message: 'il faut remplir le type.'
    )]
    private ?string $type_revenue = null;

    public function getType_revenue(): ?string
    {
        return $this->type_revenue;
    }

    public function setType_revenue(string $type_revenue): self
    {
        $this->type_revenue = $type_revenue;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_revenue = null;

    public function getDate_revenue(): ?\DateTimeInterface
    {
        return $this->date_revenue;
    }

    public function setDate_revenue(\DateTimeInterface $date_revenue): self
    {
        $this->date_revenue = $date_revenue;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Positive(
        message: 'Le montant total doit être un nombre positif.'
    )]
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

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Positive( message: 'La commission doit être un entier positif.'
    )]
    private ?float $commission = null;

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): self
    {
        $this->commission = $commission;
        return $this;
    }

    public function getIdRevenue(): ?int
    {
        return $this->id_revenue;
    }

    public function getTypeRevenue(): ?string
    {
        return $this->type_revenue;
    }

    public function setTypeRevenue(string $type_revenue): static
    {
        $this->type_revenue = $type_revenue;

        return $this;
    }

    public function getDateRevenue(): ?\DateTimeInterface
    {
        return $this->date_revenue;
    }

    public function setDateRevenue(\DateTimeInterface $date_revenue): static
    {
        $this->date_revenue = $date_revenue;

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

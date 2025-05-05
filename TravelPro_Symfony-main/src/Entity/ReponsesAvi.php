<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReponsesAviRepository;

#[ORM\Entity(repositoryClass: ReponsesAviRepository::class)]
#[ORM\Table(name: 'reponses_avis')]
class ReponsesAvi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_reponse = null;

    public function getId_reponse(): ?int
    {
        return $this->id_reponse;
    }

    public function setId_reponse(int $id_reponse): self
    {
        $this->id_reponse = $id_reponse;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Avi::class, inversedBy: 'reponsesAvis')]
    #[ORM\JoinColumn(name: 'id_avis', referencedColumnName: 'id_avis')]
    private ?Avi $avi = null;

    public function getAvi(): ?Avi
    {
        return $this->avi;
    }

    public function setAvi(?Avi $avi): self
    {
        $this->avi = $avi;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $reponse = null;

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_reponse = null;

    public function getDate_reponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDate_reponse(\DateTimeInterface $date_reponse): self
    {
        $this->date_reponse = $date_reponse;
        return $this;
    }

    public function getIdReponse(): ?int
    {
        return $this->id_reponse;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(\DateTimeInterface $date_reponse): static
    {
        $this->date_reponse = $date_reponse;

        return $this;
    }

}

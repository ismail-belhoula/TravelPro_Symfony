<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReponsesAviRepository;

#[ORM\Entity(repositoryClass: ReponsesAviRepository::class)]
#[ORM\Table(name: 'reponses_avis')]
class ReponsesAvi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_reponse = null;

    public function getIdreponse(): ?int
    {
        return $this->id_reponse;
    }

    public function setIdreponse(int $id_reponse): self
    {
        $this->id_reponse = $id_reponse;
        return $this;
    }

  #[ORM\ManyToOne(targetEntity: Avi::class, inversedBy: 'reponsesAvis')]
  #[ORM\JoinColumn(name: 'id_avis', referencedColumnName: 'id_avis')]
  #[Assert\NotNull(message: "L'avis associé est obligatoire")]
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
  #[Assert\NotBlank(message: "La réponse ne peut pas être vide")]
  #[Assert\Length(
    min: 10,
    max: 1000,
    minMessage: "La réponse doit faire au moins {{ limit }} caractères",
    maxMessage: "La réponse ne peut pas dépasser {{ limit }} caractères"
  )]
  private ?string $reponse = null;

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;
        return $this;
    }

  #[ORM\Column(type: 'datetime', nullable: false)]
  #[Assert\NotNull(message: "La date de réponse est obligatoire")]
  #[Assert\LessThanOrEqual(
    value: "now",
    message: "La date de réponse ne peut pas être dans le futur"
  )]
  private ?\DateTimeInterface $date_reponse = null;

    public function getDatereponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDatereponse(?\DateTimeInterface $date_reponse): self
    {
        $this->date_reponse = $date_reponse;
        return $this;
    }

}

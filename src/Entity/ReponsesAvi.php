<?php

namespace App\Entity;

use App\Repository\ReponsesAviRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponsesAviRepository::class)]
#[ORM\Table(name: 'reponses_avis')]
class ReponsesAvi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id_reponse = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    #[Assert\NotBlank(message: "La réponse ne peut pas être vide")]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: "La réponse doit faire au moins {{ limit }} caractères",
        maxMessage: "La réponse ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $reponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Assert\NotNull(message: "La date de réponse est obligatoire")]
    #[Assert\LessThanOrEqual(
        value: "now",
        message: "La date de réponse ne peut pas être dans le futur"
    )]
    private ?\DateTimeInterface $date_reponse = null;

    #[ORM\ManyToOne(targetEntity: Avi::class, inversedBy: 'reponsesAvis')]
    #[ORM\JoinColumn(name: 'id_avis', referencedColumnName: 'id_avis', nullable: false)]
    #[Assert\NotNull(message: "L'avis associé est obligatoire")]
    private ?Avi $avi = null;

    public function __construct()
    {
        $this->date_reponse = new \DateTime();
    }

    public function getIdReponse(): ?int
    {
        return $this->id_reponse;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;
        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(\DateTimeInterface $date_reponse): self
    {
        $this->date_reponse = $date_reponse;
        return $this;
    }

    public function getAvi(): ?Avi
    {
        return $this->avi;
    }

    public function setAvi(?Avi $avi): self
    {
        $this->avi = $avi;
        return $this;
    }
}
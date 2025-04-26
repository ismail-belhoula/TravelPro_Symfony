<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\DemandeValidationRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandeValidationRepository::class)]
#[ORM\Table(name: 'demande_validation')]
class DemandeValidation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'demandeValidations')]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id_client')]
    private ?Client $client = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Choice(choices: ['en_attente', 'validee', 'refusee'], message: 'Le statut doit être en_attente, validee ou refusee')]
    private ?string $statut = 'en_attente';

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\NotNull(message: 'La date de demande est obligatoire')]
    private ?\DateTimeInterface $date_demande = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $descriptionfr = null;

    public function __construct()
    {
        $this->date_demande = new \DateTime();
        $this->statut = 'en_attente';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDate_demande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDate_demande(\DateTimeInterface $date_demande): self
    {
        $this->date_demande = $date_demande;
        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTimeInterface $date_demande): self
    {
        $this->date_demande = $date_demande;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescriptionfr(): ?string
    {
        return $this->descriptionfr;
    }

    public function setDescriptionfr(?string $descriptionfr): self
    {
        $this->descriptionfr = $descriptionfr;
        return $this;
    }
}
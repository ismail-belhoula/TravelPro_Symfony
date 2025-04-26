<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\AviRepository;

#[ORM\Entity(repositoryClass: AviRepository::class)]
#[ORM\Table(name: 'avis')]
class Avi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_avis', type: Types::INTEGER)]
    private ?int $id_avis = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[Assert\NotBlank(message: "La note est obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: "La note doit être entre {{ min }} et {{ max }}"
    )]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    #[Assert\NotBlank(message: "Le commentaire est obligatoire")]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: "Le commentaire doit faire au moins {{ limit }} caractères",
        maxMessage: "Le commentaire ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Assert\NotBlank(message: "La date de publication est obligatoire")]
    #[Assert\Type("\DateTimeInterface", message: "La date doit être une date valide")]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    private bool $est_accepte = false;

    #[ORM\OneToMany(mappedBy: 'avi', targetEntity: ReponsesAvi::class, orphanRemoval: true)]
    private Collection $reponsesAvis;

    public function __construct()
    {
        $this->reponsesAvis = new ArrayCollection();
        $this->date_publication = new \DateTime();
        $this->est_accepte = false;
    }

    public function getIdAvis(): ?int
    {
        return $this->id_avis;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(?\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;
        return $this;
    }

    public function isEstAccepte(): bool
    {
        return $this->est_accepte;
    }

    public function setEstAccepte(bool $est_accepte): self
    {
        $this->est_accepte = $est_accepte;
        return $this;
    }

    /**
     * @return Collection<int, ReponsesAvi>
     */
    public function getReponsesAvis(): Collection
    {
        return $this->reponsesAvis;
    }

    public function addReponsesAvi(ReponsesAvi $reponsesAvi): self
    {
        if (!$this->reponsesAvis->contains($reponsesAvi)) {
            $this->reponsesAvis->add($reponsesAvi);
            $reponsesAvi->setAvi($this);
        }
        return $this;
    }

    public function removeReponsesAvi(ReponsesAvi $reponsesAvi): self
    {
        if ($this->reponsesAvis->removeElement($reponsesAvi)) {
            if ($reponsesAvi->getAvi() === $this) {
                $reponsesAvi->setAvi(null);
            }
        }
        return $this;
    }
}
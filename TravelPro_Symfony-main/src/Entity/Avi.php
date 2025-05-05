<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AviRepository;

#[ORM\Entity(repositoryClass: AviRepository::class)]
#[ORM\Table(name: 'avis')]
class Avi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_avis = null;

    public function getId_avis(): ?int
    {
        return $this->id_avis;
    }

    public function setId_avis(int $id_avis): self
    {
        $this->id_avis = $id_avis;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $note = null;

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $commentaire = null;

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_publication = null;

    public function getDate_publication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDate_publication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;
        return $this;
    }

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $est_accepte = null;

    public function isEst_accepte(): ?bool
    {
        return $this->est_accepte;
    }

    public function setEst_accepte(bool $est_accepte): self
    {
        $this->est_accepte = $est_accepte;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: ReponsesAvi::class, mappedBy: 'avi')]
    private Collection $reponsesAvis;

    public function __construct()
    {
        $this->reponsesAvis = new ArrayCollection();
    }

    /**
     * @return Collection<int, ReponsesAvi>
     */
    public function getReponsesAvis(): Collection
    {
        if (!$this->reponsesAvis instanceof Collection) {
            $this->reponsesAvis = new ArrayCollection();
        }
        return $this->reponsesAvis;
    }

    public function addReponsesAvi(ReponsesAvi $reponsesAvi): self
    {
        if (!$this->getReponsesAvis()->contains($reponsesAvi)) {
            $this->getReponsesAvis()->add($reponsesAvi);
        }
        return $this;
    }

    public function removeReponsesAvi(ReponsesAvi $reponsesAvi): self
    {
        $this->getReponsesAvis()->removeElement($reponsesAvi);
        return $this;
    }

    public function getIdAvis(): ?int
    {
        return $this->id_avis;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function isEstAccepte(): ?bool
    {
        return $this->est_accepte;
    }

    public function setEstAccepte(bool $est_accepte): static
    {
        $this->est_accepte = $est_accepte;

        return $this;
    }

}

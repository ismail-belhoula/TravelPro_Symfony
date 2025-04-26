<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AdminRepository;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: 'admin')]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_admin = null;

    public function getId_admin(): ?int
    {
        return $this->id_admin;
    }

    public function setId_admin(int $id_admin): self
    {
        $this->id_admin = $id_admin;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'admins')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur')]
    private ?Utilisateur $utilisateur = null;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }


    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }



}

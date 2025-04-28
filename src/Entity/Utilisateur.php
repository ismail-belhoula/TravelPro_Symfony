<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UtilisateurRepository;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_utilisateur = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\-\'\s]+$/u',
        message: 'Le nom ne doit contenir que des lettres.'
    )]
    private ?string $nom_utilisateur = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\-\'\s]+$/u',
        message: 'Le prénom ne doit contenir que des lettres.'
    )]
    private ?string $Prenom = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    #[Assert\Email(message: 'Le format de l\'email n\'est pas valide.')]
    private ?string $mail_utilisateur = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(
        min: 6,
        max: 100,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $mot_de_passe_utilisateur = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $role_utilisateur = null;

    #[ORM\Column(name: "code_verification", type: "string", length: 6, nullable: false)]
    private ?string $code_verification = "000000";

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $etat = null;

    #[ORM\OneToMany(targetEntity: Admin::class, mappedBy: 'utilisateur')]
    private Collection $admins;

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'utilisateur')]
    private Collection $clients;

    public function __construct()
    {
        $this->admins = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    // 🔁 Getters / Setters simplifiés

    public function getIdUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nom_utilisateur;
    }

    public function setNomUtilisateur(string $nom_utilisateur): static
    {
        $this->nom_utilisateur = $nom_utilisateur;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;
        return $this;
    }

    public function getMailUtilisateur(): ?string
    {
        return $this->mail_utilisateur;
    }

    public function setMailUtilisateur(string $mail_utilisateur): static
    {
        $this->mail_utilisateur = $mail_utilisateur;
        return $this;
    }

    public function getMotDePasseUtilisateur(): ?string
    {
        return $this->mot_de_passe_utilisateur;
    }

    public function setMotDePasseUtilisateur(?string $mot_de_passe_utilisateur): static
    {
        $this->mot_de_passe_utilisateur = $mot_de_passe_utilisateur;
        return $this;
    }

    public function getRoleUtilisateur(): ?string
    {
        return $this->role_utilisateur;
    }

    public function setRoleUtilisateur(string $role_utilisateur): static
    {
        $this->role_utilisateur = $role_utilisateur;
        return $this;
    }

    public function getCodeVerification(): ?string
    {
        return $this->code_verification;
    }

   
    public function setCodeVerification(string $code_verification): static
    {
        $this->code_verification = $code_verification;
        return $this;
    }
       
        

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function addAdmin(Admin $admin): static
    {
        if (!$this->admins->contains($admin)) {
            $this->admins->add($admin);
        }
        return $this;
    }

    public function removeAdmin(Admin $admin): static
    {
        $this->admins->removeElement($admin);
        return $this;
    }

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }
        return $this;
    }

    public function removeClient(Client $client): static
    {
        $this->clients->removeElement($client);
        return $this;
    }
}

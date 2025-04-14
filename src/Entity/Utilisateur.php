<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UtilisateurRepository;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_utilisateur = null;

    public function getId_utilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function setId_utilisateur(int $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom_utilisateur = null;

    public function getNom_utilisateur(): ?string
    {
        return $this->nom_utilisateur;
    }

    public function setNom_utilisateur(string $nom_utilisateur): self
    {
        $this->nom_utilisateur = $nom_utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Prenom = null;

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $mail_utilisateur = null;

    public function getMail_utilisateur(): ?string
    {
        return $this->mail_utilisateur;
    }

    public function setMail_utilisateur(string $mail_utilisateur): self
    {
        $this->mail_utilisateur = $mail_utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $mot_de_passe_utilisateur = null;

    public function getMot_de_passe_utilisateur(): ?string
    {
        return $this->mot_de_passe_utilisateur;
    }

    public function setMot_de_passe_utilisateur(?string $mot_de_passe_utilisateur): self
    {
        $this->mot_de_passe_utilisateur = $mot_de_passe_utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $role_utilisateur = null;

    public function getRole_utilisateur(): ?string
    {
        return $this->role_utilisateur;
    }

    public function setRole_utilisateur(string $role_utilisateur): self
    {
        $this->role_utilisateur = $role_utilisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $code_verification = null;

    public function getCode_verification(): ?string
    {
        return $this->code_verification;
    }

    public function setCode_verification(string $code_verification): self
    {
        $this->code_verification = $code_verification;
        return $this;
    }

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $etat = null;

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Admin::class, mappedBy: 'utilisateur')]
    private Collection $admins;

    /**
     * @return Collection<int, Admin>
     */
    public function getAdmins(): Collection
    {
        if (!$this->admins instanceof Collection) {
            $this->admins = new ArrayCollection();
        }
        return $this->admins;
    }

    public function addAdmin(Admin $admin): self
    {
        if (!$this->getAdmins()->contains($admin)) {
            $this->getAdmins()->add($admin);
        }
        return $this;
    }

    public function removeAdmin(Admin $admin): self
    {
        $this->getAdmins()->removeElement($admin);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'utilisateur')]
    private Collection $clients;

    public function __construct()
    {
        $this->admins = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        if (!$this->clients instanceof Collection) {
            $this->clients = new ArrayCollection();
        }
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->getClients()->contains($client)) {
            $this->getClients()->add($client);
        }
        return $this;
    }

    public function removeClient(Client $client): self
    {
        $this->getClients()->removeElement($client);
        return $this;
    }

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

}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservation')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_reservation", type: 'integer')]
    private ?int $id_reservation = null;

    #[ORM\ManyToOne(targetEntity: Voiture::class, inversedBy: 'reservations')]
#[ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture', onDelete: 'CASCADE')]
private ?Voiture $voiture = null;

#[ORM\ManyToOne(targetEntity: Billetavion::class, inversedBy: 'reservations')]
#[ORM\JoinColumn(name: 'id_billetAvion', referencedColumnName: 'id_billetavion', onDelete: 'CASCADE')]
private ?Billetavion $billetAvion = null;

#[ORM\ManyToOne(targetEntity: Hotel::class, inversedBy: 'reservations')]
#[ORM\JoinColumn(name: 'id_hotel', referencedColumnName: 'id_hotel', onDelete: 'CASCADE')]
private ?Hotel $hotel = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id_client')]
    private ?Client $client = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $statut = null;

    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): self
    {
        $this->voiture = $voiture;
        return $this;
    }

    public function getBilletAvion(): ?Billetavion
    {
        return $this->billetAvion;
    }

    public function setBilletAvion(?Billetavion $billetAvion): self
    {
        $this->billetAvion = $billetAvion;
        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;
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

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}
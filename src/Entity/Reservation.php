<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\Table(name="reservation")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id_reservation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_voiture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_billetAvion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_hotel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_client;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    // Getters and setters

    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }

    public function setIdReservation(int $id_reservation): self
    {
        $this->id_reservation = $id_reservation;

        return $this;
    }

    public function getIdVoiture(): ?int
    {
        return $this->id_voiture;
    }

    public function setIdVoiture(?int $id_voiture): self
    {
        $this->id_voiture = $id_voiture;

        return $this;
    }

    public function getIdBilletAvion(): ?int
    {
        return $this->id_billetAvion;
    }

    public function setIdBilletAvion(?int $id_billetAvion): self
    {
        $this->id_billetAvion = $id_billetAvion;

        return $this;
    }

    public function getIdHotel(): ?int
    {
        return $this->id_hotel;
    }

    public function setIdHotel(?int $id_hotel): self
    {
        $this->id_hotel = $id_hotel;

        return $this;
    }

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function setIdClient(?int $id_client): self
    {
        $this->id_client = $id_client;

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

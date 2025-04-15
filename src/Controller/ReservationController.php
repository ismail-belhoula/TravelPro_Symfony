<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Voiture;
use App\Entity\Billetavion;
use App\Entity\Hotel;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Gestion de la soumission du formulaire (POST)
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $hotelId = $request->request->get('hotel_id');
            $billetId = $request->request->get('billet_id');
            $voitureId = $request->request->get('voiture_id');
            
            // Créer et enregistrer la réservation
            $reservation = new Reservation();
            
            if ($hotelId) {
                $hotel = $entityManager->getRepository(Hotel::class)->find($hotelId);
                $reservation->setHotel($hotel);
            }
            
            if ($billetId) {
                $billet = $entityManager->getRepository(Billetavion::class)->find($billetId);
                $reservation->setBilletAvion($billet);
            }
            
            if ($voitureId) {
                $voiture = $entityManager->getRepository(Voiture::class)->find($voitureId);
                $reservation->setVoiture($voiture);
            }
            
            $reservation->setStatut('en_attente');
            // $reservation->setClient($client); // À adapter selon votre logique
            
            $entityManager->persist($reservation);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_reservation_show', ['id_reservation' => $reservation->getIdReservation()], Response::HTTP_SEE_OTHER);
        }

        // Gestion de l'affichage du formulaire (GET)
        $villeDepart = $request->query->get('villeDepart');
        $villeArrivee = $request->query->get('villeArrivee');
        $dateDepart = $request->query->get('dateDepart');
        $dateArrivee = $request->query->get('dateArrivee');
        $typeChambre = $request->query->get('typeChambre');

        // Récupérer les éléments disponibles selon les critères
        $hotels = $entityManager->getRepository(Hotel::class)->findByCriteria($villeArrivee, $typeChambre);
        $billets = $entityManager->getRepository(Billetavion::class)->findByCriteria($villeDepart, $villeArrivee, $dateDepart);
        $voitures = $entityManager->getRepository(Voiture::class)->findByCriteria($villeArrivee, $dateDepart, $dateArrivee);

        return $this->render('reservation/new.html.twig', [
            'hotels' => $hotels,
            'billets' => $billets,
            'voitures' => $voitures,
        ]);
    }

    #[Route('/{id_reservation}', name: 'app_reservation_show', methods: ['GET'])]
    public function show($id_reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy(['id_reservation' => $id_reservation]);
        
        if (!$reservation) {
            throw $this->createNotFoundException('La réservation n\'existe pas');
        }

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id_reservation}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id_reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy(['id_reservation' => $id_reservation]);
        
        if (!$reservation) {
            throw $this->createNotFoundException('La réservation n\'existe pas');
        }

        if ($request->isMethod('POST')) {
            // Récupérer le nouveau statut
            $newStatut = $request->request->get('statut');
            
            // Valider le statut
            $validStatuts = ['en_attente', 'confirme', 'annule'];
            if (!in_array($newStatut, $validStatuts)) {
                $this->addFlash('error', 'Statut invalide');
                return $this->redirectToRoute('app_reservation_edit', ['id_reservation' => $id_reservation]);
            }
            
            // Mettre à jour et sauvegarder
            $reservation->setStatut($newStatut);
            $entityManager->flush();
            
            $this->addFlash('success', 'Statut mis à jour avec succès');
            return $this->redirectToRoute('app_reservation_show', ['id_reservation' => $id_reservation]);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id_reservation}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, $id_reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy(['id_reservation' => $id_reservation]);
        
        if (!$reservation) {
            throw $this->createNotFoundException('La réservation n\'existe pas');
        }

        if ($this->isCsrfTokenValid('delete'.$reservation->getIdReservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
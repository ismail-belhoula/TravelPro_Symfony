<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Voiture;
use App\Entity\Billetavion;
use App\Entity\Hotel;
use App\Entity\Client;
use App\Service\EmailService;
use Psr\Log\LoggerInterface;
use Knp\Snappy\Pdf;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    private $logger;
    private $pdf;

    public function __construct(LoggerInterface $logger, Pdf $pdf)
    {
        $this->logger = $logger;
        $this->pdf = $pdf;
    }

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EmailService $emailService): Response
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
            // Set client_id to 1
            $client = $entityManager->getRepository(Client::class)->find(1);
            if ($client) {
                $reservation->setClient($client);
            } else {
                throw $this->createNotFoundException('Client avec ID 1 n\'existe pas');
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            // Envoyer l'email de confirmation
            $reservationDetails = [
                'hotel' => $hotel ?? null,
                'billet' => $billet ?? null,
                'voiture' => $voiture ?? null,
            ];

            $utilisateur = $client->getUtilisateur();
            if ($utilisateur && $utilisateur->getMailUtilisateur()) {
                $emailService->sendReservationConfirmation(
                    $utilisateur->getMailUtilisateur(),
                    $utilisateur->getNomUtilisateur(),
                    $reservationDetails
                );
                $this->addFlash('success', 'Mail de création de la réservation envoyé');
            } else {
                $this->addFlash('warning', 'L\'email de confirmation n\'a pas pu être envoyé car l\'adresse email du client est manquante.');
            }

            // Redirect to new reservation page instead of show
            return $this->redirectToRoute('app_reservation_new', [], Response::HTTP_SEE_OTHER);
        }

        // Gestion de l'affichage du formulaire (GET)
        $villeDepart = $request->query->get('villeDepart');
        $villeArrivee = $request->query->get('villeArrivee');
        $dateDepart = $request->query->get('dateDepart');
        $dateArrivee = $request->query->get('dateArrivee');
        $typeChambre = $request->query->get('typeChambre');

        // Validation des paramètres requis
        if (!$villeArrivee || !$typeChambre) {
            return $this->render('reservation/new.html.twig', [
                'hotels' => [],
                'billets' => [],
                'voitures' => [],
                'error' => 'Veuillez fournir une ville d\'arrivée et un type de chambre.',
            ]);
        }

        // Convertir les dates en objets DateTime
        try {
            $dateDepartObj = $dateDepart ? new \DateTime($dateDepart) : null;
            $dateArriveeObj = $dateArrivee ? new \DateTime($dateArrivee) : null;
        } catch (\Exception $e) {
            return $this->render('reservation/new.html.twig', [
                'hotels' => [],
                'billets' => [],
                'voitures' => [],
                'error' => 'Format de date invalide.',
            ]);
        }

        // Récupérer les éléments disponibles selon les critères
        $hotels = $entityManager->getRepository(Hotel::class)->findByCriteria($villeArrivee, $typeChambre, $dateDepartObj, $dateArriveeObj);
        $billets = $entityManager->getRepository(Billetavion::class)->findByCriteria($villeDepart, $villeArrivee, $dateDepart);
        $voitures = $entityManager->getRepository(Voiture::class)->findByCriteria($dateDepart, $dateArrivee);

        return $this->render('reservation/new.html.twig', [
            'hotels' => $hotels,
            'billets' => $billets,
            'voitures' => $voitures,
        ]);
    }

    #[Route('/search', name: 'app_reservation_search', methods: ['GET'])]
    public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les paramètres de recherche
        $villeDepart = $request->query->get('villeDepart');
        $villeArrivee = $request->query->get('villeArrivee');
        $dateDepart = $request->query->get('dateDepart');
        $dateArrivee = $request->query->get('dateArrivee');
        $typeChambre = $request->query->get('typeChambre');

        // Convertir les dates en objets DateTime
        try {
            $dateDepartObj = $dateDepart ? new \DateTime($dateDepart) : null;
            $dateArriveeObj = $dateArrivee ? new \DateTime($dateArrivee) : null;
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer les éléments disponibles selon les critères
        $hotels = $entityManager->getRepository(Hotel::class)->findByCriteria(
            $villeArrivee,
            $typeChambre,
            $dateDepartObj,
            $dateArriveeObj
        );
        $billets = $entityManager->getRepository(Billetavion::class)->findByCriteria(
            $villeDepart,
            $villeArrivee,
            $dateDepart
        );
        $voitures = $entityManager->getRepository(Voiture::class)->findByCriteria(
            $dateDepart,
            $dateArrivee
        );

        // Préparer les données pour la réponse JSON
        $hotelsData = array_map(function (Hotel $hotel) {
            return [
                'idHotel' => $hotel->getIdHotel(),
                'nom' => $hotel->getNom(),
                'ville' => $hotel->getVille(),
                'nombreEtoile' => $hotel->getNombreEtoile(),
                'typeDeChambre' => $hotel->getTypeDeChambre(),
                'prixParNuit' => $hotel->getPrixParNuit(),
                'dateCheckIn' => $hotel->getDateCheckIn() ? $hotel->getDateCheckIn()->format('d/m/Y') : null,
                'dateCheckOut' => $hotel->getDateCheckOut() ? $hotel->getDateCheckOut()->format('d/m/Y') : null,
            ];
        }, $hotels);

        $billetsData = array_map(function (Billetavion $billet) {
            return [
                'idBilletavion' => $billet->getIdBilletavion(),
                'compagnie' => $billet->getCompagnie(),
                'villeDepart' => $billet->getVilleDepart(),
                'villeArrivee' => $billet->getVilleArrivee(),
                'dateDepart' => $billet->getDateDepart()->format('d/m/Y'),
                'classBillet' => $billet->getClassBillet(),
                'prix' => $billet->getPrix(),
            ];
        }, $billets);

        $voituresData = array_map(function (Voiture $voiture) {
            return [
                'id_voiture' => $voiture->getId_voiture(),
                'marque' => $voiture->getMarque(),
                'modele' => $voiture->getModele(),
                'annee' => $voiture->getAnnee(),
                'dateDeLocation' => $voiture->getDateDeLocation()->format('d/m/Y'),
                'dateDeRemise' => $voiture->getDateDeRemise()->format('d/m/Y'),
                'prixParJour' => $voiture->getPrixParJour(),
            ];
        }, $voitures);

        return new JsonResponse([
            'hotels' => $hotelsData,
            'billets' => $billetsData,
            'voitures' => $voituresData,
        ]);
    }

    #[Route('/quick-search', name: 'app_reservation_quick_search', methods: ['GET'])]
    public function quickSearch(Request $request, ReservationRepository $reservationRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            $this->logger->info("Quick search initiated with query: {$query}");

            if (empty($query) || strlen($query) < 2) {
                $this->logger->info("Query empty or too short, returning empty result");
                return new JsonResponse(['reservations' => []]);
            }

            $reservations = $reservationRepository->searchByQuery($query);
            $this->logger->info("Found " . count($reservations) . " reservations for query: {$query}");

            $reservationsData = array_map(function (Reservation $reservation) {
                return [
                    'nom' => $reservation->getClient() && $reservation->getClient()->getUtilisateur() ? $reservation->getClient()->getUtilisateur()->getNomUtilisateur() : '',
                    'prenom' => $reservation->getClient() && $reservation->getClient()->getUtilisateur() ? $reservation->getClient()->getUtilisateur()->getPrenom() : '',
                    'voiture' => $reservation->getVoiture() ? $reservation->getVoiture()->getMarque() : '',
                    'billetAvion' => $reservation->getBilletAvion() ? $reservation->getBilletAvion()->getCompagnie() : '',
                    'hotel' => $reservation->getHotel() ? $reservation->getHotel()->getNom() : '',
                    'statut' => $reservation->getStatut() ?? '',
                    'showUrl' => $this->generateUrl('app_reservation_show', ['id_reservation' => $reservation->getIdReservation()]),
                    'editUrl' => $this->generateUrl('app_reservation_edit', ['id_reservation' => $reservation->getIdReservation()]),
                    'deleteUrl' => $this->generateUrl('app_reservation_delete', ['id_reservation' => $reservation->getIdReservation()]),
                    '_token' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $reservation->getIdReservation())->getValue(),
                ];
            }, $reservations);

            return new JsonResponse(['reservations' => $reservationsData]);
        } catch (\Exception $e) {
            $this->logger->error("Error in quick search: " . $e->getMessage());
            return new JsonResponse(['error' => 'Erreur serveur lors de la recherche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
            $this->logger->error("Reservation with ID $id_reservation not found");
            throw $this->createNotFoundException('La réservation n\'existe pas');
        }

        // Get the redirect target from the form
        $redirectTo = $request->request->get('redirect_to', 'app_reservation_index');
        $validRedirects = ['app_reservation_index', 'app_reservations_client'];
        
        // Log the redirect target for debugging
        $this->logger->info("Delete reservation $id_reservation, redirect_to: $redirectTo");

        // Ensure the redirect target is valid, default to app_reservation_index if not
        if (!in_array($redirectTo, $validRedirects)) {
            $this->logger->warning("Invalid redirect_to value: $redirectTo, defaulting to app_reservation_index");
            $redirectTo = 'app_reservation_index';
        }

        if ($this->isCsrfTokenValid('delete'.$reservation->getIdReservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation supprimée avec succès');
            $this->logger->info("Reservation $id_reservation deleted successfully");
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
            $this->logger->error("Invalid CSRF token for reservation $id_reservation deletion");
        }

        return $this->redirectToRoute($redirectTo, [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reservations/client', name: 'app_reservations_client', methods: ['GET'])]
    public function clientReservations(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        // Default to client ID 1 as per requirement
        $client = $entityManager->getRepository(Client::class)->find(1);
        
        if (!$client) {
            $this->addFlash('error', 'Client avec ID 1 non trouvé.');
            return $this->render('reservation/reservations_client.html.twig', [
                'reservations' => [],
            ]);
        }

        // Fetch reservations for the client
        $reservations = $reservationRepository->findBy(
            ['client' => $client],
            ['id_reservation' => 'DESC']
        );

        return $this->render('reservation/reservations_client.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/{id_reservation}/pdf', name: 'app_reservation_pdf', methods: ['GET'])]
    public function downloadPdf($id_reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy(['id_reservation' => $id_reservation]);

        if (!$reservation) {
            $this->logger->error("Reservation with ID $id_reservation not found for PDF generation");
            throw $this->createNotFoundException('La réservation n\'existe pas');
        }

        // Vérifier que la réservation appartient au client (ID 1 pour cet exemple)
        $client = $entityManager->getRepository(Client::class)->find(1);
        if ($reservation->getClient() !== $client) {
            $this->logger->error("Client ID 1 not authorized to access reservation $id_reservation");
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à télécharger ce PDF.');
        }

        // Rendre le template HTML pour le PDF
        $html = $this->renderView('reservation/pdf.html.twig', [
            'reservation' => $reservation,
            'client' => $client,
        ]);

        // Générer le PDF
        $filename = sprintf('reservation-%s.pdf', $reservation->getIdReservation());
        $pdfContent = $this->pdf->getOutputFromHtml($html, [
            'encoding' => 'UTF-8',
            'margin-top' => 10,
            'margin-bottom' => 10,
            'margin-left' => 15,
            'margin-right' => 15,
            'disable-smart-shrinking' => true, // Reduces rendering complexity
            'dpi' => 96, // Lower DPI for faster rendering
            'no-outline' => true, // Disables outlines for faster processing
        ]);

        // Créer la réponse avec le PDF
        $response = new Response($pdfContent);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', $disposition);

        $this->logger->info("PDF generated for reservation $id_reservation");
        return $response;
    }
}
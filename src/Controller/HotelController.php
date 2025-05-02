<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/hotel')]
class HotelController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_hotel_index', methods: ['GET'])]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    #[Route('/quick-search', name: 'app_hotel_quick_search', methods: ['GET'])]
    public function quickSearch(Request $request, HotelRepository $hotelRepository): JsonResponse
    {
        $query = $request->query->get('query', '');

        if (empty($query)) {
            $this->logger->info('Quick search: Empty query provided');
            return $this->json(['error' => 'Query parameter is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->logger->info("Quick search initiated with query: {$query}");
            $hotels = $hotelRepository->searchByQuery($query);

            $data = array_map(function (Hotel $hotel) {
                return [
                    'id' => $hotel->getIdHotel(),
                    'nom' => $hotel->getNom(),
                    'ville' => $hotel->getVille(),
                    'prixParNuit' => $hotel->getPrixParNuit(),
                    'disponible' => $hotel->isDisponible() ? 'Yes' : 'No',
                    'nombreEtoile' => $hotel->getNombreEtoile(),
                    'typeDeChambre' => $hotel->getTypeDeChambre(),
                    'dateCheckIn' => $hotel->getDateCheckIn() ? $hotel->getDateCheckIn()->format('Y-m-d') : '',
                    'dateCheckOut' => $hotel->getDateCheckOut() ? $hotel->getDateCheckOut()->format('Y-m-d') : '',
                    'showUrl' => $this->generateUrl('app_hotel_show', ['id_hotel' => $hotel->getIdHotel()]),
                    'editUrl' => $this->generateUrl('app_hotel_edit', ['id_hotel' => $hotel->getIdHotel()]),
                    'deleteUrl' => $this->generateUrl('app_hotel_delete', ['id_hotel' => $hotel->getIdHotel()]),
                    '_token' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $hotel->getIdHotel())->getValue(),
                ];
            }, $hotels);

            return $this->json(['hotels' => $data]);
        } catch (\Exception $e) {
            $this->logger->error("Quick search error: {$e->getMessage()}");
            return $this->json(['error' => 'An error occurred during the search'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new', name: 'app_hotel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hotel);
            $entityManager->flush();

            $this->addFlash('success', 'Hôtel créé avec succès');
            return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id_hotel}', name: 'app_hotel_show', methods: ['GET'])]
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    #[Route('/{id_hotel}/edit', name: 'app_hotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Hôtel modifié avec succès');
            return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id_hotel}', name: 'app_hotel_delete', methods: ['POST'])]
    public function delete(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hotel->getIdHotel(), $request->request->get('_token'))) {
            $entityManager->remove($hotel);
            $entityManager->flush();
            $this->addFlash('success', 'Hôtel et ses réservations supprimés avec succès');
        }

        return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
    }
}
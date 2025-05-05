<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/voiture')]
final class VoitureController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(name: 'app_voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    #[Route('/quick-search', name: 'app_voiture_quick_search', methods: ['GET'])]
    public function quickSearch(Request $request, VoitureRepository $voitureRepository): JsonResponse
    {
        $query = $request->query->get('query', '');

        if (empty($query)) {
            $this->logger->info('Quick search: Empty query provided');
            return $this->json(['error' => 'Query parameter is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->logger->info("Quick search initiated with query: {$query}");
            $voitures = $voitureRepository->searchByQuery($query);

            $data = array_map(function (Voiture $voiture) {
                return [
                    'id' => $voiture->getId_voiture(),
                    'marque' => $voiture->getMarque(),
                    'modele' => $voiture->getModele(),
                    'annee' => $voiture->getAnnee(),
                    'prixParJour' => $voiture->getPrixParJour(),
                    'disponible' => $voiture->isDisponible() ? 'Yes' : 'No',
                    'dateDeLocation' => $voiture->getDateDeLocation() ? $voiture->getDateDeLocation()->format('Y-m-d') : '',
                    'dateDeRemise' => $voiture->getDateDeRemise() ? $voiture->getDateDeRemise()->format('Y-m-d') : '',
                    'showUrl' => $this->generateUrl('app_voiture_show', ['id_voiture' => $voiture->getId_voiture()]),
                    'editUrl' => $this->generateUrl('app_voiture_edit', ['id_voiture' => $voiture->getId_voiture()]),
                    'deleteUrl' => $this->generateUrl('app_voiture_delete', ['id_voiture' => $voiture->getId_voiture()]),
                    '_token' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $voiture->getId_voiture())->getValue(),
                ];
            }, $voitures);

            return $this->json(['voitures' => $data]);
        } catch (\Exception $e) {
            $this->logger->error("Quick search error: {$e->getMessage()}");
            return $this->json(['error' => 'An error occurred during the search'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voiture);
            $entityManager->flush();

            $this->addFlash('success', 'Voiture créée avec succès');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id_voiture}', name: 'app_voiture_show', methods: ['GET'])]
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/{id_voiture}/edit', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Voiture modifiée avec succès');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id_voiture}', name: 'app_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $voiture->getId_voiture(), $request->request->get('_token'))) {
            $entityManager->remove($voiture);
            $entityManager->flush();
            $this->addFlash('success', 'Voiture et ses réservations supprimées avec succès');
        }

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
}
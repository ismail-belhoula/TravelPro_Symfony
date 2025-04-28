<?php

namespace App\Controller;

use App\Entity\Billetavion;
use App\Form\BilletavionType;
use App\Repository\BilletavionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/billetavion')]
final class BilletavionController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(name: 'app_billetavion_index', methods: ['GET'])]
    public function index(BilletavionRepository $billetavionRepository): Response
    {
        return $this->render('billetavion/index.html.twig', [
            'billetavions' => $billetavionRepository->findAll(),
        ]);
    }

    #[Route('/quick-search', name: 'app_billetavion_quick_search', methods: ['GET'])]
    public function quickSearch(Request $request, BilletavionRepository $billetavionRepository): JsonResponse
    {
        $query = $request->query->get('query', '');

        if (empty($query)) {
            $this->logger->info('Quick search: Empty query provided');
            return $this->json(['error' => 'Query parameter is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->logger->info("Quick search initiated with query: {$query}");
            $billetavions = $billetavionRepository->searchByQuery($query);

            $data = array_map(function (Billetavion $billetavion) {
                return [
                    'id' => $billetavion->getIdBilletavion(),
                    'compagnie' => $billetavion->getCompagnie(),
                    'classBillet' => $billetavion->getClassBillet(),
                    'villeDepart' => $billetavion->getVilleDepart(),
                    'villeArrivee' => $billetavion->getVilleArrivee(),
                    'dateDepart' => $billetavion->getDateDepart() ? $billetavion->getDateDepart()->format('Y-m-d') : '',
                    'dateArrivee' => $billetavion->getDateArrivee() ? $billetavion->getDateArrivee()->format('Y-m-d') : '',
                    'prix' => $billetavion->getPrix(),
                    'showUrl' => $this->generateUrl('app_billetavion_show', ['id_billetavion' => $billetavion->getIdBilletavion()]),
                    'editUrl' => $this->generateUrl('app_billetavion_edit', ['id_billetavion' => $billetavion->getIdBilletavion()]),
                    'deleteUrl' => $this->generateUrl('app_billetavion_delete', ['id_billetavion' => $billetavion->getIdBilletavion()]),
                    '_token' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $billetavion->getIdBilletavion())->getValue(),
                ];
            }, $billetavions);

            return $this->json(['billetavions' => $data]);
        } catch (\Exception $e) {
            $this->logger->error("Quick search error: {$e->getMessage()}");
            return $this->json(['error' => 'An error occurred during the search'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new', name: 'app_billetavion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $billetavion = new Billetavion();
        $form = $this->createForm(BilletavionType::class, $billetavion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($billetavion);
            $entityManager->flush();

            return $this->redirectToRoute('app_billetavion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('billetavion/new.html.twig', [
            'billetavion' => $billetavion,
            'form' => $form,
        ]);
    }

    #[Route('/{id_billetavion}', name: 'app_billetavion_show', methods: ['GET'])]
    public function show(Billetavion $billetavion): Response
    {
        return $this->render('billetavion/show.html.twig', [
            'billetavion' => $billetavion,
        ]);
    }

    #[Route('/{id_billetavion}/edit', name: 'app_billetavion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Billetavion $billetavion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BilletavionType::class, $billetavion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_billetavion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('billetavion/edit.html.twig', [
            'billetavion' => $billetavion,
            'form' => $form,
        ]);
    }

    #[Route('/{id_billetavion}', name: 'app_billetavion_delete', methods: ['POST'])]
    public function delete(Request $request, Billetavion $billetavion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $billetavion->getIdBilletavion(), $request->request->get('_token'))) {
            $entityManager->remove($billetavion);
            $entityManager->flush();
            $this->addFlash('success', 'Billet d\'avion et ses réservations supprimés avec succès');
        }

        return $this->redirectToRoute('app_billetavion_index', [], Response::HTTP_SEE_OTHER);
    }
}
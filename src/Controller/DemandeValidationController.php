<?php

namespace App\Controller;

use App\Entity\DemandeValidation;
use App\Form\DemandeValidationType;
use App\Repository\DemandeValidationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demande/validation')]
final class DemandeValidationController extends AbstractController
{
    #[Route(name: 'app_demande_validation_index', methods: ['GET'])]
    public function index(DemandeValidationRepository $demandeValidationRepository): Response
    {
        return $this->render('demande_validation/index.html.twig', [
            'demande_validations' => $demandeValidationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_demande_validation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demandeValidation = new DemandeValidation();
        $form = $this->createForm(DemandeValidationType::class, $demandeValidation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeValidation);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_validation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_validation/new.html.twig', [
            'demande_validation' => $demandeValidation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_validation_show', methods: ['GET'])]
    public function show(DemandeValidation $demandeValidation): Response
    {
        return $this->render('demande_validation/show.html.twig', [
            'demande_validation' => $demandeValidation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_validation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeValidation $demandeValidation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeValidationType::class, $demandeValidation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_validation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_validation/edit.html.twig', [
            'demande_validation' => $demandeValidation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_validation_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeValidation $demandeValidation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeValidation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandeValidation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_validation_index', [], Response::HTTP_SEE_OTHER);
    }
}

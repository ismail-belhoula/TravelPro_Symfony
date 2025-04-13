<?php

namespace App\Controller;

use App\Entity\Deponse;
use App\Form\DeponseType;
use App\Repository\DeponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/deponse')]
final class DeponseController extends AbstractController
{
    #[Route(name: 'app_deponse_index', methods: ['GET'])]
    public function index(DeponseRepository $deponseRepository): Response
    {
        return $this->render('deponse/index.html.twig', [
            'deponses' => $deponseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_deponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deponse = new Deponse();
        $form = $this->createForm(DeponseType::class, $deponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($deponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_deponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('deponse/new.html.twig', [
            'deponse' => $deponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id_deponse}', name: 'app_deponse_show', methods: ['GET'])]
    public function show(Deponse $deponse): Response
    {
        return $this->render('deponse/show.html.twig', [
            'deponse' => $deponse,
        ]);
    }

    #[Route('/{id_deponse}/edit', name: 'app_deponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Deponse $deponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeponseType::class, $deponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_deponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('deponse/edit.html.twig', [
            'deponse' => $deponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id_deponse}', name: 'app_deponse_delete', methods: ['POST'])]
    public function delete(Request $request, Deponse $deponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deponse->getId_deponse(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($deponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_deponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
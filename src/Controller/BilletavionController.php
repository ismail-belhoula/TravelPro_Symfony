<?php

namespace App\Controller;

use App\Entity\Billetavion;
use App\Form\BilletavionType;
use App\Repository\BilletavionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/billetavion')]
final class BilletavionController extends AbstractController
{
    #[Route(name: 'app_billetavion_index', methods: ['GET'])]
    public function index(BilletavionRepository $billetavionRepository): Response
    {
        return $this->render('billetavion/index.html.twig', [
            'billetavions' => $billetavionRepository->findAll(),
        ]);
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
    if ($this->isCsrfTokenValid('delete'.$billetavion->getIdBilletavion(), $request->request->get('_token'))) {
        $entityManager->remove($billetavion);
        $entityManager->flush();
        $this->addFlash('success', 'Billet d\'avion et ses réservations supprimés avec succès');
    }

    return $this->redirectToRoute('app_billetavion_index', [], Response::HTTP_SEE_OTHER);
}
}

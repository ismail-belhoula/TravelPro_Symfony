<?php

namespace App\Controller;

use App\Entity\Revenue;
use App\Form\RevenueType;
use App\Repository\RevenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/revenue')]
final class RevenueController extends AbstractController
{
    #[Route(name: 'app_revenue_index', methods: ['GET'])]
    public function index(RevenueRepository $revenueRepository): Response
    {
        return $this->render('revenue/index.html.twig', [
            'revenues' => $revenueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_revenue_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $revenue = new Revenue();
        $form = $this->createForm(RevenueType::class, $revenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($revenue);
            $entityManager->flush();

            return $this->redirectToRoute('app_revenue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revenue/new.html.twig', [
            'revenue' => $revenue,
            'form' => $form,
        ]);
    }

    #[Route('/{id_revenue}', name: 'app_revenue_show', methods: ['GET'])]
    public function show(Revenue $revenue): Response
    {
        return $this->render('revenue/show.html.twig', [
            'revenue' => $revenue,
        ]);
    }

    #[Route('/{id_revenue}/edit', name: 'app_revenue_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Revenue $revenue, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RevenueType::class, $revenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_revenue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revenue/edit.html.twig', [
            'revenue' => $revenue,
            'form' => $form,
        ]);
    }

    #[Route('/{id_revenue}', name: 'app_revenue_delete', methods: ['POST'])]
    public function delete(Request $request, Revenue $revenue, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revenue->getId_revenue(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($revenue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_revenue_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\ReponsesAvi;
use App\Form\ReponsesAviType;
use App\Repository\ReponsesAviRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reponses/avi')]
final class ReponsesAviController extends AbstractController
{
    #[Route(name: 'app_reponses_avi_index', methods: ['GET'])]
    public function index(ReponsesAviRepository $reponsesAviRepository): Response
    {
        return $this->render('reponses_avi/index.html.twig', [
            'reponses_avis' => $reponsesAviRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponses_avi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponsesAvi = new ReponsesAvi();
        $form = $this->createForm(ReponsesAviType::class, $reponsesAvi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponsesAvi);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponses_avi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponses_avi/new.html.twig', [
            'reponses_avi' => $reponsesAvi,
            'form' => $form,
        ]);
    }

    #[Route('/{id_reponse}', name: 'app_reponses_avi_show', methods: ['GET'])]
    public function show(ReponsesAvi $reponsesAvi): Response
    {
        return $this->render('reponses_avi/show.html.twig', [
            'reponses_avi' => $reponsesAvi,
        ]);
    }

    #[Route('/{id_reponse}/edit', name: 'app_reponses_avi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponsesAvi $reponsesAvi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponsesAviType::class, $reponsesAvi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponses_avi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponses_avi/edit.html.twig', [
            'reponses_avi' => $reponsesAvi,
            'form' => $form,
        ]);
    }

    #[Route('/{id_reponse}', name: 'app_reponses_avi_delete', methods: ['POST'])]
    public function delete(Request $request, ReponsesAvi $reponsesAvi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponsesAvi->getId_reponse(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reponsesAvi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponses_avi_index', [], Response::HTTP_SEE_OTHER);
    }
}

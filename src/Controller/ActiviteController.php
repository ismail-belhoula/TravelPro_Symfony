<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activite')]
final class ActiviteController extends AbstractController
{
    #[Route(name: 'app_activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('backoffice/activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();
    
            $this->addFlash('success', 'Activité créée avec succès!');
            return $this->redirectToRoute('app_activite_index');
        }
    
        return $this->render('backoffice/activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('backoffice/activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/activite/evenement/{id}', name: 'activite_par_evenement')]
    public function activitesParEvenement(Evenement $evenement, ActiviteRepository $activiteRepository): Response
    {
        $activites = $activiteRepository->findBy(['evenement' => $evenement]);

        return $this->render('activite/show.html.twig', [
            'evenement' => $evenement,
            'activites' => $activites,
        ]);
    }
    
    

    
}

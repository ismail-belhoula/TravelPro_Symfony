<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/evenement')]
final class EvenementController extends AbstractController
{
    #[Route( name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $evenementRepository->createQueryBuilder('e')->getQuery();
    
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // page actuelle
            6 // nombre d'éléments par page
        );
    
        return $this->render('backoffice/evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'initial_lat' => $evenement->getLatitude(),
            'initail_lon' => $evenement->getLongitude(),
        ]);
    }

    #[Route('d/{id_event}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('backoffice/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('e/{id_event}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile= $form->get('imageFile')->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'initial_lat' => $evenement->getLatitude(),
            'initail_lon' => $evenement->getLongitude(),
        ]);
    }

    #[Route('s/{id_event}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId_event(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('f/{id_event}', name: 'app_evenement_showfront', methods: ['GET'])]
    public function showfront(Evenement $evenement): Response
    {
        return $this->render('/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/event', name: 'front_event', methods: ['GET'])]
    public function indexFront(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $evenementRepository->createQueryBuilder('e')
            ->orderBy('e.id_event', 'ASC'); // ou 'e.date_debut', 'ASC'
    
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6
        );
    
        return $this->render('/evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    
    
}

<?php

namespace App\Controller;

use App\Entity\Avi;
use App\Form\AviFrontType;
use App\Repository\AviRepository;
use App\Service\ProfanityFilterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AviFrontController extends AbstractController
{
  #[Route('/avis', name: 'app_avi_front_index', methods: ['GET'])]
  public function indexFront(AviRepository $aviRepository): Response
  {
    // Liste seulement les avis acceptés
    $avis = $aviRepository->findBy(['est_accepte' => true], ['date_publication' => 'DESC']);

    return $this->render('avifront/index.html.twig', [
      'avis' => $avis,
    ]);
  }

  #[Route('/avis/ajouter', name: 'app_avi_front_new', methods: ['GET', 'POST'])]
  public function newFront(Request $request, EntityManagerInterface $entityManager, ProfanityFilterService $profanityFilter): Response
  {
    $avi = new Avi();
    $avi->setEstaccepte(false);
    $avi->setDatepublication(new \DateTime());

    $form = $this->createForm(AviFrontType::class, $avi);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Filter the comment text before saving
      $filteredComment = $profanityFilter->filterText($avi->getCommentaire());
      $avi->setCommentaire($filteredComment);
      
      $entityManager->persist($avi);
      $entityManager->flush();

      $this->addFlash('success', 'Votre avis a été soumis avec succès!');
      return $this->redirectToRoute('app_avi_front_index');
    }

    return $this->render('avifront/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/avis/{id_avis}', name: 'app_avi_front_show', methods: ['GET'])]
  public function showFront(Avi $avi): Response
  {
    if (!$avi->isEstaccepte()) {
      throw $this->createNotFoundException('Cet avis n\'est pas disponible');
    }

    return $this->render('avifront/show.html.twig', [
      'avi' => $avi,
    ]);
  }

  #[Route('/avis/{id_avis}/modifier', name: 'app_avi_front_edit', methods: ['GET', 'POST'])]
  public function editFront(Request $request, Avi $avi, EntityManagerInterface $entityManager, ProfanityFilterService $profanityFilter): Response
  {
    $form = $this->createForm(AviFrontType::class, $avi);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Filter the comment text before saving
      $filteredComment = $profanityFilter->filterText($avi->getCommentaire());
      $avi->setCommentaire($filteredComment);
      
      $entityManager->flush();

      $this->addFlash('success', 'L\'avis a été modifié avec succès!');
      return $this->redirectToRoute('app_avi_front_show', ['id_avis' => $avi->getIdavis()]);
    }

    return $this->render('avifront/edit.html.twig', [
      'avi' => $avi,
      'form' => $form->createView(),
    ]);
  }

  #[Route('/avis/{id_avis}', name: 'app_avi_front_delete', methods: ['POST'])]
  public function deleteFront(Request $request, Avi $avi, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete'.$avi->getIdavis(), $request->request->get('_token'))) {
      $entityManager->remove($avi);
      $entityManager->flush();
      $this->addFlash('success', 'L\'avis a été supprimé avec succès!');
    }

    return $this->redirectToRoute('app_avi_front_index');
  }
}

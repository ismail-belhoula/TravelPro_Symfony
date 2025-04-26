<?php

namespace App\Controller;

use App\Entity\ReponsesAvi;
use App\Entity\Avi;
use App\Form\ReponsesAviFrontType;
use App\Repository\ReponsesAviRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponses-avis')]
class ReponsesAviFrontController extends AbstractController
{
  #[Route('/', name: 'app_reponses_avi_front_index', methods: ['GET'])]
  public function indexFront(ReponsesAviRepository $reponsesAviRepository): Response
  {
    return $this->render('reponse_avi_front/index.html.twig', [
      'reponses_avis' => $reponsesAviRepository->findAll(),
    ]);
  }

  #[Route('/new/{id_avis}', name: 'app_reponses_avi_front_new', methods: ['GET', 'POST'])]
  public function newFront(Request $request, Avi $avi, EntityManagerInterface $entityManager): Response
  {
    $reponse = new ReponsesAvi();
    $reponse->setAvi($avi);
    $reponse->setDatereponse(new \DateTime());

    $form = $this->createForm(ReponsesAviFrontType::class, $reponse, [
      'avi_id' => $avi->getIdavis()
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($reponse);
      $entityManager->flush();

      $this->addFlash('success', 'Votre réponse a été enregistrée avec succès!');
      return $this->redirectToRoute('app_reponses_avi_front_show', [
        'id_reponse' => $reponse->getIdreponse()
      ], Response::HTTP_SEE_OTHER);
    }

    return $this->render('reponse_avi_front/new.html.twig', [
      'reponse' => $reponse,
      'form' => $form->createView(),
      'avi' => $avi
    ]);
  }

  #[Route('/{id_reponse}', name: 'app_reponses_avi_front_show', methods: ['GET'])]
  public function showFront(ReponsesAvi $reponse): Response
  {
    return $this->render('reponse_avi_front/show.html.twig', [
      'reponse' => $reponse,
    ]);
  }

  #[Route('/{id_reponse}/edit', name: 'app_reponses_avi_front_edit', methods: ['GET', 'POST'])]
  public function editFront(Request $request, ReponsesAvi $reponse, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ReponsesAviFrontType::class, $reponse, [
      'avi_id' => $reponse->getAvi()->getIdavis()
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'La réponse a été modifiée avec succès!');
      return $this->redirectToRoute('app_reponses_avi_front_show', [
        'id_reponse' => $reponse->getIdreponse()
      ], Response::HTTP_SEE_OTHER);
    }

    return $this->render('reponse_avi_front/edit.html.twig', [
      'reponse' => $reponse,
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id_reponse}', name: 'app_reponses_avi_front_delete', methods: ['POST'])]
  public function deleteFront(Request $request, ReponsesAvi $reponse, EntityManagerInterface $entityManager): Response
  {
    $avi = $reponse->getAvi();

    if ($this->isCsrfTokenValid('delete'.$reponse->getIdreponse(), $request->request->get('_token'))) {
      $entityManager->remove($reponse);
      $entityManager->flush();
      $this->addFlash('success', 'La réponse a été supprimée avec succès!');
    }

    return $this->redirectToRoute('app_avi_front_show', [
      'id_avis' => $avi->getIdavis()
    ], Response::HTTP_SEE_OTHER);
  }
}

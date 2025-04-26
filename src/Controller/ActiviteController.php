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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
    #[Route('/activite/export', name: 'app_activite_export')]
public function export(ActiviteRepository $activiteRepository): Response
{
    $activites = $activiteRepository->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // En-têtes avec style
    $sheet->setCellValue('A1', 'ID Activité');
    $sheet->setCellValue('B1', 'Nom');
    $sheet->setCellValue('C1', 'Description');
    $sheet->setCellValue('D1', 'Date début');
    $sheet->setCellValue('E1', 'Date fin');
    $sheet->setCellValue('F1', 'Événement associé');
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    // Remplissage des données
    $row = 2;
    foreach ($activites as $activite) {
        $sheet->setCellValue('A' . $row, $activite->getId());
        $sheet->setCellValue('B' . $row, $activite->getNomActivite());
        $sheet->setCellValue('C' . $row, $activite->getDescription());
        $sheet->setCellValue('D' . $row, $activite->getDateDebut()->format('Y-m-d'));
        $sheet->setCellValue('E' . $row, $activite->getDateFin()->format('Y-m-d'));
        $sheet->setCellValue('F' . $row, $activite->getEvenement()?->getNomEvent() ?? 'N/A');
        $row++;
    }

    // Ajustement automatique des colonnes
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Formatage conditionnel pour les dates
    $sheet->getStyle('D2:E' . ($row - 1))
          ->getNumberFormat()
          ->setFormatCode('yyyy-mm-dd');

    // Création du fichier
    $writer = new Xlsx($spreadsheet);
    $fileName = 'export_activites_' . date('Y-m-d') . '.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);

    // Envoi du fichier
    return $this->file(
        $tempFile,
        $fileName,
        ResponseHeaderBag::DISPOSITION_ATTACHMENT
    );
}
    
    

    
}

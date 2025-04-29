<?php

namespace App\Controller;

use App\Repository\DeponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request, DeponseRepository $deponseRepository): Response
    {
        // Récupération des dates de début et de fin du formulaire
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        // Vérification que les dates sont valides et création d'objets DateTime
        $startDateObj = $startDate ? \DateTime::createFromFormat('Y-m-d', $startDate)->setTime(0, 0, 0) : null;
        $endDateObj = $endDate ? \DateTime::createFromFormat('Y-m-d', $endDate)->setTime(23, 59, 59) : null;

        // Debug: vérifier les dates avant d'envoyer à la méthode du repository
        dump($startDateObj, $endDateObj);

        // Récupérer les dépenses filtrées par plage de dates
        $deponses = $deponseRepository->findByDateRange($startDateObj, $endDateObj);

        // Debug: vérifier si des résultats sont renvoyés
        dump($deponses);

        // Calcul des totaux par mois
        $totauxParMois = [];
        foreach ($deponses as $deponse) {
            $mois = $deponse->getDate_achat()->format('Y-m'); // Format : année-mois
            if (!isset($totauxParMois[$mois])) {
                $totauxParMois[$mois] = 0;
            }
            $totauxParMois[$mois] += $deponse->getPrix_achat() * $deponse->getQuantite_produit();
        }

        dump(array_keys($totauxParMois));
        dump(array_values($totauxParMois));
        dump($startDate);
        dump($endDate) ;

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'mois' => $mois,
                'totaux' => $totaux,
            ]);
        }
        return $this->render('dashboard/dashboard.html.twig', [
            'mois' => array_keys($totauxParMois),
            'totaux' => array_values($totauxParMois),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    #[Route('/dashboard2', name: 'dashboard')]
    public function index2(): Response
    {
        return $this->render('backoffice/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}

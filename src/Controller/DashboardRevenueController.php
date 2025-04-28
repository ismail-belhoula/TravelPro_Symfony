<?php

namespace App\Controller;

use App\Repository\RevenueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardRevenueController extends AbstractController
{
    #[Route('/dashboard_revenue', name: 'app_dashboard_revenue')]
    public function index(Request $request, RevenueRepository $revenueRepository): Response
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
        $revenues = $revenueRepository->findByDateRange($startDateObj, $endDateObj);

        // Debug: vérifier si des résultats sont renvoyés
        dump($revenues);

        // Calcul des totaux par mois
        $totauxParMois = [];
        foreach ($revenues as $revenue) {
            $mois = $revenue->getDate_revenue()->format('Y-m'); // Format : année-mois
            if (!isset($totauxParMois[$mois])) {
                $totauxParMois[$mois] = 0;
            }
            $totauxParMois[$mois] += $revenue->getMontant_total();
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
        return $this->render('dashboard/dashboardrevenue.html.twig', [
            'mois' => array_keys($totauxParMois),
            'totaux' => array_values($totauxParMois),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}





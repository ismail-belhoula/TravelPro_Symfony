<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'boutique')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('boutique/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/cart', name: 'cart')]
    public function cart(): Response
    {
        return $this->render('cart/index.html.twig');
    }
}
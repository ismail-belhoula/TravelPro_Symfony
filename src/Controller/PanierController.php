<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/panier')]
final class PanierController extends AbstractController
{
    #[Route(name: 'app_panier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $conn = $entityManager->getConnection();
        
        // Get cart total
        $sql = "SELECT COALESCE(SUM(p.prix_vente * pp.quantite), 0) as total
                FROM panier_produit pp
                JOIN produit p ON p.id_produit = pp.id_produit
                WHERE pp.id_panier = 1";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $cartData = $result->fetchAssociative();
        $total = $cartData['total'];

        // Get cart items
        $sql = "SELECT 
                pp.id_panier,
                pp.id_produit,
                pp.quantite,
                p.nom_produit,
                p.prix_vente,
                p.image_path,
                p.quantite_produit,
                (p.prix_vente * pp.quantite) as sous_total
                FROM panier_produit pp
                JOIN produit p ON p.id_produit = pp.id_produit
                WHERE pp.id_panier = 1";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $cartItems = $result->fetchAllAssociative();

        return $this->render('panier/index.html.twig', [
            'panier_produits' => $cartItems, // Changed from 'paniers' to 'panier_produits'
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY'],
            'total' => $total,
        ]);
    }

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id_panier}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id_panier}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id_panier}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId_panier(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add', name: 'app_panier_add', methods: ['POST'])]
public function addToPanier(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $idProduit = $request->request->get('id_produit');
    $quantite = (int) $request->request->get('quantite');
    $user = $this->getUser();

    if (!$user || !$idProduit || $quantite <= 0) {
        return new JsonResponse(['success' => false, 'message' => 'Données invalides.'], 400);
    }

    $produit = $entityManager->getRepository(Produit::class)->find($idProduit);

    if (!$produit) {
        return new JsonResponse(['success' => false, 'message' => 'Produit introuvable.'], 404);
    }

    $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $user]);

    if (!$panier) {
        $panier = new Panier();
        $panier->setClient($user);
        $entityManager->persist($panier);
        $entityManager->flush();
    }

    $panierProduit = $entityManager->getRepository(PanierProduit::class)->findOneBy([
        'panier' => $panier,
        'produit' => $produit,
    ]);

    if ($panierProduit) {
        $panierProduit->setQuantite($panierProduit->getQuantite() + $quantite);
    } else {
        $panierProduit = new PanierProduit();
        $panierProduit->setPanier($panier);
        $panierProduit->setProduit($produit);
        $panierProduit->setQuantite($quantite);
        $entityManager->persist($panierProduit);
    }

    $entityManager->flush();

    return new JsonResponse(['success' => true, 'message' => 'Produit ajouté au panier avec succès !']);
}
}






<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'boutique')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get all products from the database
        $conn = $entityManager->getConnection();
        $sql = "SELECT 
                id_produit AS idProduit,
                nom_produit AS nomProduit,
                prix_achat AS prixAchat,
                quantite_produit AS quantiteProduit,
                prix_vente AS prixVente,
                image_path AS imagePath
                FROM produit 
                WHERE quantite_produit > 0";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $produits = $result->fetchAllAssociative();

        return $this->render('boutique/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['productId']) || !isset($data['quantity'])) {
                return new JsonResponse(['error' => 'Données manquantes'], 400);
            }

            $productId = $data['productId'];
            $quantity = (int)$data['quantity'];
            
            $conn = $entityManager->getConnection();
            
            // Get product price
            $sql = "SELECT prix_vente FROM produit WHERE id_produit = :productId";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery(['productId' => $productId]);
            $product = $result->fetchAssociative();
            
            if (!$product) {
                return new JsonResponse(['error' => 'Produit non trouvé'], 404);
            }
            
            $prix = $product['prix_vente'];
            $montant = $prix * $quantity;

            // First check if the product exists in the cart
            $sql = "SELECT quantite FROM panier_produit 
                   WHERE id_panier = 1 AND id_produit = :productId";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery(['productId' => $productId]);
            $existing = $result->fetchAssociative();

            if ($existing) {
                // Update existing quantity and update panier total
                $sql = "UPDATE panier_produit 
                       SET quantite = quantite + :quantity 
                       WHERE id_panier = 1 AND id_produit = :productId";
                $stmt = $conn->prepare($sql);
                $stmt->executeStatement([
                    'productId' => $productId,
                    'quantity' => $quantity
                ]);
            } else {
                // Insert new record
                $sql = "INSERT INTO panier_produit (id_panier, id_produit, quantite) 
                       VALUES (1, :productId, :quantity)";
                $stmt = $conn->prepare($sql);
                $stmt->executeStatement([
                    'productId' => $productId,
                    'quantity' => $quantity
                ]);
            }

            // Update panier total
            $sql = "UPDATE panier 
                   SET montant_total = (
                       SELECT SUM(p.prix_vente * pp.quantite)
                       FROM panier_produit pp
                       JOIN produit p ON p.id_produit = pp.id_produit
                       WHERE pp.id_panier = 1
                   )
                   WHERE id_panier = 1";
            $stmt = $conn->prepare($sql);
            $stmt->executeStatement();

            return new JsonResponse(['success' => true]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Une erreur est survenue lors de l\'ajout au panier'
            ], 500);
        }
    }

    #[Route('/boutique/search', name: 'boutique_search')]
    public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $searchTerm = $request->query->get('q', '');
        $sortPrice = $request->query->get('sort', '');

        $conn = $entityManager->getConnection();
        
        $sql = "SELECT 
                id_produit AS idProduit,
                nom_produit AS nomProduit,
                prix_achat AS prixAchat,
                quantite_produit AS quantiteProduit,
                prix_vente AS prixVente,
                image_path AS imagePath
                FROM produit 
                WHERE quantite_produit > 0";
        
        $params = [];
        
        if (!empty($searchTerm)) {
            $sql .= " AND LOWER(nom_produit) LIKE LOWER(:searchTerm)";
            $params['searchTerm'] = '%' . $searchTerm . '%';
        }

        if ($sortPrice === 'asc') {
            $sql .= " ORDER BY prix_vente ASC";
        } elseif ($sortPrice === 'desc') {
            $sql .= " ORDER BY prix_vente DESC";
        }

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery($params);
        $produits = $result->fetchAllAssociative();

        $html = $this->renderView('boutique/_products.html.twig', [
            'produits' => $produits
        ]);

        return new JsonResponse([
            'html' => $html
        ]);
    }
}

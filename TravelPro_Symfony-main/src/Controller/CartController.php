<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $conn = $entityManager->getConnection();
        
        // Get cart items with their details
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

        // Get total from panier table
        $sql = "SELECT montant_total FROM panier WHERE id_panier = 1";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $panier = $result->fetchAssociative();
        
        return $this->render('cart/index.html.twig', [
            'cart_items' => $cartItems,
            'total' => $panier['montant_total'] ?? 0,
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
        ]);
    }

    #[Route('/remove-from-cart/{productId}', name: 'remove_from_cart', methods: ['POST'])]
    public function removeFromCart(int $productId, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $conn = $entityManager->getConnection();

            // Delete the product from panier_produit
            $sql = "DELETE FROM panier_produit 
                   WHERE id_panier = 1 AND id_produit = :productId";
            $stmt = $conn->prepare($sql);
            $stmt->executeStatement(['productId' => $productId]);

            // Update panier total
            $sql = "UPDATE panier 
                   SET montant_total = (
                       SELECT COALESCE(SUM(p.prix_vente * pp.quantite), 0)
                       FROM panier_produit pp
                       JOIN produit p ON p.id_produit = pp.id_produit
                       WHERE pp.id_panier = 1
                   )
                   WHERE id_panier = 1";
            $stmt = $conn->prepare($sql);
            $stmt->executeStatement();

            // Get new total
            $sql = "SELECT montant_total FROM panier WHERE id_panier = 1";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery();
            $panier = $result->fetchAssociative();

            return new JsonResponse([
                'success' => true,
                'newTotal' => $panier['montant_total'] ?? 0
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Une erreur est survenue lors de la suppression'
            ], 500);
        }
    }

    #[Route('/update-cart-quantity', name: 'update_cart_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $productId = $data['productId'];
            $quantity = (int)$data['quantity'];
            
            if ($quantity < 1) {
                return new JsonResponse([
                    'error' => 'La quantité doit être supérieure à 0'
                ], 400);
            }

            $conn = $entityManager->getConnection();

            // Check if product has enough stock
            $sql = "SELECT quantite_produit FROM produit WHERE id_produit = :productId";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery(['productId' => $productId]);
            $product = $result->fetchAssociative();

            if (!$product || $quantity > $product['quantite_produit']) {
                return new JsonResponse([
                    'error' => 'Quantité non disponible en stock'
                ], 400);
            }

            // Update quantity in panier_produit
            $sql = "UPDATE panier_produit 
                   SET quantite = :quantity 
                   WHERE id_panier = 1 AND id_produit = :productId";
            $stmt = $conn->prepare($sql);
            $stmt->executeStatement([
                'productId' => $productId,
                'quantity' => $quantity
            ]);

            // Update total in panier
            $sql = "UPDATE panier 
                   SET montant_total = (
                       SELECT COALESCE(SUM(p.prix_vente * pp.quantite), 0)
                       FROM panier_produit pp
                       JOIN produit p ON p.id_produit = pp.id_produit
                       WHERE pp.id_panier = 1
                   )
                   WHERE id_panier = 1";
            $stmt = $conn->prepare($sql);
            $stmt->executeStatement();

            // Get updated cart information
            $sql = "SELECT 
                    pp.quantite,
                    (p.prix_vente * pp.quantite) as sous_total,
                    (SELECT montant_total FROM panier WHERE id_panier = 1) as total
                    FROM panier_produit pp
                    JOIN produit p ON p.id_produit = pp.id_produit
                    WHERE pp.id_panier = 1 AND pp.id_produit = :productId";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery(['productId' => $productId]);
            $updatedItem = $result->fetchAssociative();

            return new JsonResponse([
                'success' => true,
                'quantity' => $updatedItem['quantite'],
                'subTotal' => $updatedItem['sous_total'],
                'total' => $updatedItem['total']
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Une erreur est survenue lors de la mise à jour'
            ], 500);
        }
    }
}







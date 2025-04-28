<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class PaymentController extends AbstractController
{
    private $logger;
    private const TND_TO_USD_RATE = 0.32; // Conversion rate: 1 TND = 0.32 USD

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function convertTNDtoUSD(float $amountTND): int
    {
        // Convert TND to USD and return amount in cents
        return (int)round($amountTND * self::TND_TO_USD_RATE * 100);
    }

    #[Route('/create-checkout-session', name: 'create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(EntityManagerInterface $em, SessionInterface $session): JsonResponse
    {
        try {
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            $cartQuery = "SELECT 
                            p.nom_produit,
                            p.prix_vente,
                            pp.quantite
                         FROM panier_produit pp
                         JOIN produit p ON pp.id_produit = p.id_produit 
                         WHERE pp.id_panier = 1";
            
            $stmt = $em->getConnection()->prepare($cartQuery);
            $result = $stmt->executeQuery();
            $cartItems = $result->fetchAllAssociative();

            if (empty($cartItems)) {
                throw new \Exception('Le panier est vide');
            }

            // Store cart total in session for verification later
            $cartTotal = array_sum(array_map(function($item) {
                return $item['prix_vente'] * $item['quantite'];
            }, $cartItems));
            $session->set('cart_total', $cartTotal);

            $lineItems = [];
            foreach ($cartItems as $item) {
                $priceInUSDCents = $this->convertTNDtoUSD((float)$item['prix_vente']);
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $priceInUSDCents,
                        'product_data' => [
                            'name' => $item['nom_produit'],
                            'description' => sprintf('Prix original: %.3f TND', $item['prix_vente']),
                        ],
                    ],
                    'quantity' => $item['quantite'],
                ];
            }

            // Add shipping fee (7 TND converted to USD)
            $shippingFeeUSD = $this->convertTNDtoUSD(7.000);
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $shippingFeeUSD,
                    'product_data' => [
                        'name' => 'Frais de livraison',
                        'description' => 'Frais de livraison (7.000 TND)',
                    ],
                ],
                'quantity' => 1,
            ];

            $stripeSession = \Stripe\Checkout\Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $this->generateUrl('app_panier_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'shipping_address_collection' => [
                    'allowed_countries' => ['TN'],
                ],
                'locale' => 'fr',
                'customer_creation' => 'always',
            ]);

            return new JsonResponse(['id' => $stripeSession->id]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }
    
    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(Request $request, EntityManagerInterface $em, LoggerInterface $logger, SessionInterface $session): Response
    {
        try {
            $sessionId = $request->query->get('session_id');
            if (!$sessionId) {
                throw new \Exception('Session ID not found');
            }

            // Verify payment with Stripe
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            $stripeSession = \Stripe\Checkout\Session::retrieve($sessionId);
            
            if ($stripeSession->payment_status !== 'paid') {
                throw new \Exception('Payment not completed');
            }

            $conn = $em->getConnection();
            $conn->beginTransaction();

            try {
                // Get cart total
                $sql = "SELECT COALESCE(SUM(p.prix_vente * pp.quantite), 0) as total
                        FROM panier_produit pp
                        JOIN produit p ON p.id_produit = pp.id_produit
                        WHERE pp.id_panier = 1";
                $stmt = $conn->prepare($sql);
                $result = $stmt->executeQuery();
                $cartData = $result->fetchAssociative();
                $total = (float)$cartData['total'];

                $logger->info('Cart total calculated', ['total' => $total]);

                // 1. Create new commande
                $sql = "INSERT INTO commande (id_client, montant_total, date_commande, status) 
                       VALUES (1, :total_amount, NOW(), 'completed')";
                $stmt = $conn->prepare($sql);
                $stmt->executeStatement(['total_amount' => $total]);
                
                $newCommandeId = $conn->lastInsertId();
                $logger->info('New commande created', ['id' => $newCommandeId]);

                // 2. Copy products from panier_produit to commande_produit
                $sql = "INSERT INTO commande_produit (id_commande, id_produit, quantite, prix_vente)
                       SELECT :commande_id, pp.id_produit, pp.quantite, p.prix_vente
                       FROM panier_produit pp
                       JOIN produit p ON p.id_produit = pp.id_produit
                       WHERE pp.id_panier = 1";
                $stmt = $conn->prepare($sql);
                $stmt->executeStatement(['commande_id' => $newCommandeId]);
                
                // 3. Clear the cart (panier_produit)
                $sql = "DELETE FROM panier_produit WHERE id_panier = 1";
                $stmt = $conn->prepare($sql);
                $result = $stmt->executeStatement();
                $logger->info('Cleared cart items', ['rows_affected' => $result]);

                // 4. Reset panier total
                $sql = "UPDATE panier SET montant_total = 0 WHERE id_panier = 1";
                $stmt = $conn->prepare($sql);
                $result = $stmt->executeStatement();
                $logger->info('Reset panier total', ['rows_affected' => $result]);

                $conn->commit();
                $logger->info('Transaction committed successfully');

                // Verify the cart is empty
                $sql = "SELECT COUNT(*) as count FROM panier_produit WHERE id_panier = 1";
                $stmt = $conn->prepare($sql);
                $result = $stmt->executeQuery();
                $count = $result->fetchOne();
                $logger->info('Cart items after transaction', ['count' => $count]);

                // Verify the commande was created
                $sql = "SELECT * FROM commande WHERE id_commande = :id";
                $stmt = $conn->prepare($sql);
                $result = $stmt->executeQuery(['id' => $newCommandeId]);
                $commande = $result->fetchAssociative();
                $logger->info('Commande verification', ['commande' => $commande]);

                return $this->render('payment/success.html.twig', [
                    'order_id' => $newCommandeId
                ]);

            } catch (\Exception $e) {
                $conn->rollBack();
                $logger->error('Error in transaction', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            $logger->error('Payment verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->redirectToRoute('app_panier_index', ['error' => 'Payment verification failed']);
        }
    }

    #[Route('/download-invoice/{id}', name: 'download_invoice')]
    public function downloadInvoice(int $id, EntityManagerInterface $em): Response
    {
        try {
            // Fetch order details
            $conn = $em->getConnection();
            $sql = "SELECT c.*, cp.quantite, cp.prix_vente, p.nom_produit
                    FROM commande c
                    JOIN commande_produit cp ON c.id_commande = cp.id_commande
                    JOIN produit p ON cp.id_produit = p.id_produit
                    WHERE c.id_commande = :id";
            
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery(['id' => $id]);
            $orderDetails = $result->fetchAllAssociative();

            if (empty($orderDetails)) {
                throw new \Exception('Commande non trouvée');
            }

            // Configure Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);

            // Generate HTML for the invoice
            $html = $this->renderView('payment/invoice_template.html.twig', [
                'order' => $orderDetails[0],
                'items' => $orderDetails,
                'date' => new \DateTime($orderDetails[0]['date_commande'])
            ]);

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the PDF
            $dompdf->render();

            // Generate a unique filename
            $filename = sprintf('facture-%s-%s.pdf', $id, date('Y-m-d'));

            // Return the PDF as a response
            return new Response(
                $dompdf->output(),
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
                ]
            );

        } catch (\Exception $e) {
            return $this->redirectToRoute('boutique');
        }
    }
}



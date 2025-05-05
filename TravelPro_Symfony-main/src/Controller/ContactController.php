<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_send', methods: ['POST'])]
    public function sendContact(Request $request, MailerInterface $mailer): Response
    {
        $name = trim($request->request->get('name'));
        $emailFrom = trim($request->request->get('email'));
        $phone = trim($request->request->get('phone'));
        $messageContent = trim($request->request->get('message'));

        if (empty($name) || empty($emailFrom) || empty($messageContent)) {
            $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
            return $this->redirectToRoute('contact_page');
        }

        // Générer le PDF
        $pdfContent = "
            <h1>Nouvelle Réclamation</h1>
            <p><strong>Nom :</strong> {$name}</p>
            <p><strong>Email :</strong> {$emailFrom}</p>
            <p><strong>Téléphone :</strong> {$phone}</p>
            <p><strong>Message :</strong><br>{$messageContent}</p>
        ";

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Chemin pour enregistrer
        $pdfFilename = 'reclamation_' . uniqid() . '.pdf';
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/reclamations/' . $pdfFilename;
        
        // Sauvegarder le PDF
        file_put_contents($pdfPath, $dompdf->output());

        // URL publique pour le lien dans l'email
        $request = Request::createFromGlobals();
$baseUrl = $request->getSchemeAndHttpHost(); // ex: http://127.0.0.1:8000
$pdfUrl = $baseUrl . '/uploads/reclamations/' . $pdfFilename;


        try {
            $email = (new Email())
                ->from($emailFrom)
                ->to('25k01a2003c@gmail.com')
                ->subject('Négocier un solde')
                ->html("
                    <h2>Nouvelle demande de contact :</h2>
                    <table border='1' cellpadding='10' cellspacing='0'>
                        <tr><td><strong>Nom</strong></td><td>{$name}</td></tr>
                        <tr><td><strong>Email</strong></td><td>{$emailFrom}</td></tr>
                        <tr><td><strong>Téléphone</strong></td><td>{$phone}</td></tr>
                        <tr><td><strong>Message</strong></td><td>{$messageContent}</td></tr>
                    </table>
                    <br>
                    <a href='{$pdfUrl}' style='
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #4CAF50;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    '>Télécharger la réclamation en PDF</a>
                ");

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('error', 'Erreur lors de l\'envoi de votre message : ' . $e->getMessage());
        }

        return $this->redirectToRoute('contact');
    }
}

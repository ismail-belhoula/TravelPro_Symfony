<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendInvoiceEmail(string $recipientEmail, string $pdfContent, string $orderId): void
    {
        $email = (new Email())
            ->from('TravelPro@gmail.com')
            ->to($recipientEmail)
            ->subject('Votre facture TravelPro - Commande #' . $orderId)
            ->html('<p>Bonjour,</p>
                   <p>Nous vous remercions pour votre commande. Veuillez trouver ci-joint votre facture.</p>
                   <p>Cordialement,<br>L\'équipe TravelPro</p>')
            ->attach($pdfContent, 'facture-' . $orderId . '.pdf', 'application/pdf');

        $this->mailer->send($email);
    }
    public function sendReservationConfirmation(
        string $to,
        string $clientName,
        array $reservationDetails
    ): void {
        $email = (new TemplatedEmail())
            ->from('no-reply@travelpro.com')
            ->to($to)
            ->subject('Confirmation de votre réservation')
            ->htmlTemplate('emails/reservation_confirmation.html.twig')
            ->context([
                'clientName' => $clientName,
                'reservationDetails' => $reservationDetails,
            ]);

        $this->mailer->send($email);
    }
}
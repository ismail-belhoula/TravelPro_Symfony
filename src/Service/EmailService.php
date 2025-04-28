<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
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
<?php

namespace App\Services\Mail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SymfonyMailService
{
    private MailerInterface $mailer;

    //TODO inject from mail as .env
    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }

    // TODO move this into its own mail service or send mails directly from application
    public function sendMail(ServiceMail $mail): void
    {
        $email = (new TemplatedEmail())
            ->from($mail->getFrom())
            ->to($mail->getTo())
            ->subject($mail->getSubject())
            ->htmlTemplate($mail->getTemplate())
            ->context($mail->getContext());

        $this->mailer->send($email);
    }
}

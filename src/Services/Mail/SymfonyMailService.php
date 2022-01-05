<?php

namespace App\Services\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SymfonyMailService
{
    private MailerInterface $mailer;

    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }

    // TODO move this into its own mail service or send mails directly from application
    public function sendMail(): void
    {
        $email = (new Email())
            ->from('idp@service.com')
            ->to('user@email.ch')
            ->subject('Test Mail')
            ->text('Just Text Body')
            ->html('<p>Html Text Body</p>');

        $this->mailer->send($email);
    }
}

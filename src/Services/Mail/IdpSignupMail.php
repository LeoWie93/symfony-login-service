<?php

namespace App\Services\Mail;

class IdpSignupMail extends ServiceMail
{
    public const SUBJECT = 'New Registration';

    public static function fromData(array $data): ServiceMail
    {
        return new self(
            $data['contactMail'],
            self::SUBJECT,
            'emails/signup.html.twig',
            $data,
        );
    }
}

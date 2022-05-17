<?php

namespace App\Services\Mail;

abstract class ServiceMail
{
    public const FROM_MAIL = 'idp@service.com';

    private string $from = self::FROM_MAIL;
    private string $to;
    private string $subject;
    private string $template;
    private array $context;

    public function __construct(string $to, string $subject, string $template, array $context)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->template = $template;
        $this->context = $context;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    abstract public static function fromData(array $data): ServiceMail;
}

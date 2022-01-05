<?php

namespace App\Exceptions;

/**
 * Class ServiceException.
 */
class ServiceException extends \Exception
{
    private int $httpCode;

    public function __construct(
        string $message = '',
        int $code = 0,
        int $httpCode = 500
    ) {
        $this->httpCode = $httpCode;
        parent::__construct($message, $code);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}

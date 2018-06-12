<?php

namespace UtenteDDDExample\Domain\Model\Utente\Exception;

use DDDStarterPack\Domain\Model\Exception\DomainException;
use Throwable;

class PasswordInvalidException extends DomainException
{
    const MESSAGE = 'Password non valida';

    public function __construct(string $message = "", int $code = 401, Throwable $previous = null)
    {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}

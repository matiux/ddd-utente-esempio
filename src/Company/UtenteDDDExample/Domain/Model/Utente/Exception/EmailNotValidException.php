<?php

namespace UtenteDDDExample\Domain\Model\Utente\Exception;

use DDDStarterPack\Domain\Model\Exception\DomainException;
use Throwable;

class EmailNotValidException extends DomainException
{
    const MESSAGE = 'Email non valida';

    public function __construct(string $message = "", int $code = 412, Throwable $previous = null)
    {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}

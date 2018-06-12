<?php

namespace UtenteDDDExample\Domain\Model\Utente\Exception;

use DDDStarterPack\Domain\Model\Exception\DomainException;
use Throwable;

class EmailUtenteIsNotUniqueException extends DomainException
{
    const MESSAGE = 'Email già presente';

    public function __construct(string $message = "", int $code = 409, Throwable $previous = null)
    {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace UtenteDDDExample\Domain\Model\Utente\Exception;

use DDDStarterPack\Domain\Model\Exception\DomainException;
use Throwable;

class UtenteIsLockedException extends DomainException
{
    const MESSAGE = 'Utente bloccato';

    public function __construct(string $message = "", int $code = 401, Throwable $previous = null)
    {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}

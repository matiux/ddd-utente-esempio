<?php

namespace UtenteDDDExample\Domain\Model\Utente\Exception;

use DDDStarterPack\Domain\Model\Exception\DomainException;

class RuoloNotValidException extends DomainException
{
    const MESSAGE = 'Ruolo non valido';
}

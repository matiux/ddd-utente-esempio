<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Service\Utente\CurrentUtenteAutenticato;

abstract class UtenteAutenticatoSpecification
{
    protected $currentUtenteAutenticato;

    public function __construct(CurrentUtenteAutenticato $currentUtenteAutenticato)
    {
        $this->currentUtenteAutenticato = $currentUtenteAutenticato;
    }

    abstract public function isSatisfiedBy(UtenteId $utenteId): bool;
}

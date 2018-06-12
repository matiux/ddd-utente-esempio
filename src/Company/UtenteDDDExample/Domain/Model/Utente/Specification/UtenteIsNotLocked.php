<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\Utente;

class UtenteIsNotLocked extends UtenteSpecification
{
    public function isSatisfiedBy(Utente $utente): bool
    {
        return !$utente->isLock();
    }
}

<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;

class OnlyAdminCanPerfomSomeActions extends UtenteAutenticatoSpecification
{
    public function isSatisfiedBy(UtenteId $utenteId): bool
    {
        $utenteAutenticato = $this->currentUtenteAutenticato->get()->utente();

        return (string)$utenteAutenticato->ruolo() === Ruolo::ROLE_ADMIN;
    }
}

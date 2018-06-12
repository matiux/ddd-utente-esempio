<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;

class EmailUtenteIsUnique extends EmailUtenteSpecification
{
    public function isSatisfiedBy(EmailUtente $emailUtente): bool
    {
        if ($this->utenteRepository->byEmail($emailUtente)) {
            return false;
        }

        return true;
    }
}
